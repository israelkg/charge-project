document.addEventListener('DOMContentLoaded', function () {
    //Data Client
    const clientCpfCnpjInput = document.getElementById('charge_type_form_clientCpfCnpj');
    const clientNameInput = document.getElementById('charge_type_form_clientName');
    const clientEmailInput = document.getElementById('charge_type_form_clientEmail');
    const clientPhoneInput = document.getElementById('charge_type_form_clientPhone');
    const clientAdressInput = document.getElementById('charge_type_form_clientAdress');

    const multiStepForm = document.getElementById('multi-step-form');
    const prevStep2Button = document.getElementById('prev-step-2');
    const submitFormButton = document.getElementById('submit-form');


    function showError(element) {
        element.classList.add('border-red-500');
        element.reportValidity?.();
    }
    function hideError(element) {
        if (!element || !element.classList) return;
        element.classList.remove('border-red-500');
        element.setCustomValidity?.('');
    }


    function validateCpfCnpj(){
        const cpfCnpj = clientCpfCnpjInput.trim().value;
        if(cpfCnpj === ""){
            showError(clientCpfCnpjInput)
            return false;
        }
        hideError(clientCpfCnpjInput);

        return true;
    }
    function validateName(){
        const name = clientNameInput.trim().value;
        if(name === ""){
            showError(clientNameInput)
            return false;
        }
        hideError(clientNameInput);

        return true;
    }
    function validateEmail(){
        const email = clientEmailInput.value.trim();

        if(email === ""){
            showError(clientEmailInput);
            return false;
        }
        hideError(clientEmailInput);

        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        console.log("Regex: ", regex,  "email: ", email)
        return regex.test(email);

    }
    function validatePhone(){
        const phone = clientPhoneInput.trim().value;
        if(phone === ""){
            showError(clientPhoneInput);
            return false;
        }
        hideError(clientPhoneInput);
        const regex = /^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/;
        console.log("regex: ", regex,  "     phone: ", phone);
        return regex.test(phone);
    }

    function validateStep2() {
        let isValid = true;

        Object.entries(step2Fields).forEach(([key, field]) => {
            if (!field.value || field.value.trim() === '') {
                showError(field, 'Campo obrigatório.');
                isValid = false;
            } else {
                hideError(field);
            }
        });

        return isValid;
    }

    multiStepForm.addEventListener('submit', function (e) {
        const currentStep = document.getElementById('charge_type_form_currentStep')?.value;

        if (currentStep === '2') {
            const valid = validateStep2();
            if (!valid) {
                e.preventDefault(); 
            }
        }
    });

    // submitFormButton.addEventListener('click', (event) => {
    //     let isValid = true;
    
    //     if(!validateCpfCnpj())   isValid = false;
    //     if(!validateName())      isValid = false;
    //     if(!validateEmail())     isValid = false;
    //     if(!validatePhone())     isValid = false;
    
    //     if (!isValid) {
    //         event.preventDefault();
    //         console.log(
    //             ""
    //         )
    //     }
    // });



});