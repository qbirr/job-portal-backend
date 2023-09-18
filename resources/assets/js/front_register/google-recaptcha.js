document.addEventListener('turbo:load', loadGoogleRecaptchaData);

function loadGoogleRecaptchaData() {

}

window.checkGoogleReCaptcha = function checkGoogleReCaptcha(registerType) {
    let response = grecaptcha.getResponse();
    if (response.length == 0) {
        displayErrorMessage('You must verify google recaptcha.');
        processingBtn(
            registerType == 1
                ? '#addCandidateNewForm'
                : '#addEmployerNewForm',
            registerType == 1 ? '#btnCandidateSave' : '#btnEmployerSave');

        return false;
    }

    return true;
};
