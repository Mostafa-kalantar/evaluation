import {
    blockPage,
    unblockPage,
    toastSuccess,
    toastError,
} from "@custom-helpers/ui.js";
import { router } from "@inertiajs/vue3";

function errorResponseHandler(error) {
    if (error.response) {
        switch (error.response.status) {
            case 401:
                toastError("Session is invalid");
                localStorage.removeItem("auth_token");
                router.visit(route("login"));
                break;
            case 403:
                toastError("Unauthorized!");
                break;
            case 500:
                toastError("Server Error!");
                break;
            default:
                error.response.data.errors !== "" &&
                error.response.data.errors instanceof Array
                    ? toastError(error.response.data.errors[0])
                    : "";
                break;
        }
    }
}

export async function httpRequest(
    url,
    data = {},
    method = "POST",
    content_type = "json",
    showLoading = false
) {
    axios.defaults.withXSRFToken = true;
    axios.defaults.withCredentials = true;
    axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

    let content_type_complete = "";
    if (content_type === "json") {
        content_type_complete = "application/json;charset=UTF-8";
    } else if (content_type === "form_data") {
        content_type_complete =
            "application/x-www-form-urlencoded;charset=UTF-8";
    } else {
        content_type_complete = content_type;
    }

    if (showLoading) blockPage();

    const options = {
        method: method,
        url: url,
        headers: {
            Accept: "application/json",
            "Content-Type": content_type_complete,
        },
        data: data,
    };

    const token = localStorage.getItem("auth_token");
    if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    }

    const response = await axios(options)
        .catch((error) => {
            errorResponseHandler(error);
        })
        .finally(() => {
            if (showLoading) unblockPage();
        });

    const responseOK =
        response && response.status === 200 && "data" in response;
    if (responseOK) {
        return await response.data;
    } else {
        return { status: "error", data: [] };
    }
}
