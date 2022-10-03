/** @format */

const toggleFullScreen = () => {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().then(r => {});
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen().then(r => {});
        }
    }
};

export default { toggleFullScreen };
