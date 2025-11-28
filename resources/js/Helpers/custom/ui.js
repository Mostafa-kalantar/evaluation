import { ElLoading, ElMessage } from "element-plus";

let loadingInstance = null;

export const blockPage = (text = "Loading") => {
    loadingInstance = ElLoading.service({
        lock: false,
        text: text,
        background: "rgba(0, 0, 0, 0.7)",
    });
};

export const unblockPage = () => {
    if (loadingInstance) {
        loadingInstance.close();
        loadingInstance = null;
    }
};

export const toastSuccess = (message = "Successfully done!") => {
    ElMessage.success({
        message,
        duration: 3000,
    });
};

export const toastError = (message = "There is an error") => {
    ElMessage.error({
        message,
        duration: 5000,
    });
};

export const toastWarning = (message = "Alert") => {
    ElMessage.warning({
        message,
        duration: 5000,
    });
};
export const isEnglish = (text) => {
    return /[A-Za-z]/.test(text);
};
