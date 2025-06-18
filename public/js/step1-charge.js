
document.addEventListener('DOMContentLoaded', function () {
    const multiStepForm = document.getElementById('multi-step-form');
    const stepIndicator = document.getElementById('step-indicator');
    const formSteps = document.querySelectorAll('.form-step');

    //Data Charge
    const chargeValueInput = document.getElementById('charge_form_type_value');
    const chargeTypeField = document.getElementById('charge_type_form_chargeType')
    const paymentOptionGroup = document.getElementById('payment_option_group');
    const installmentsField = document.getElementById('charge_type_form_installments');
    const installmentsRow = document.getElementById('charge_type_form_installmentsRow');
    
    //Data Client
    const clientNameInput = document.getElementById(multiStepForm?.elements['charge_type_form_clientName']?.id);
    const clientEmailInput = document.getElementById(multiStepForm?.elements['charge_type_form_clientEmail']?.id);
    const clientPhoneInput = document.getElementById(multiStepForm?.elements['charge_type_form_clientPhone']?.id);
    const clientCpfCnpjInput = document.getElementById('charge_type_form_clientCpfCnpj');
    const clientAdressInput = document.getElementById('charge_type_form_clientAdress');

    const prevStep2Button = document.getElementById('prev-step-2');
    const submitFormButton = document.getElementById('submit-form');

    const dueDateInput = document.querySelector('.datepicker-input');
    if (dueDateInput) {
        flatpickr(dueDateInput, {
            dateFormat: "d/m/Y",
            locale: "pt",
            allowInput: true,
            altInput: false,
            minDate: "today",
        });
    }

    let currentStep = 0;
    const MIN_VALUE_FOR_INSTALLMENTS = 20.00;

    function getChargeValue() {
        const raw = window.imaskInstance?.unmaskedValue || '0';
        const sanitized = raw.replace(',', '.');
        return parseFloat(sanitized) || 0;
    }

    function getSelectedPaymentOption() {
        return document.querySelector('#payment_option_group input[type="radio"]:checked')?.value;
    }

    function formatCurrency(value) {
        return window.formatCurrency?.(value) || value;
    }
    
    function updateInstallmentsOptions() {
        const chargeValue = getChargeValue();
        const selectedPaymentOption = getSelectedPaymentOption();
    
        if (!installmentsField) return;
    
        installmentsField.replaceChildren();
        
        if (selectedPaymentOption === 'A_VISTA_PARCELADO') {
            installmentsRow?.style.setProperty('display', 'block');
            installmentsField.required = true;
    
            addInstallmentOption(1, chargeValue); // à vista
    
            if (chargeValue >= MIN_VALUE_FOR_INSTALLMENTS) {
                installmentsField.disabled = false;
                for (let i = 2; i <= 10; i++) {
                    const installmentValue = chargeValue / i;
                    if (installmentValue >= 5) {
                        addInstallmentOption(i, installmentValue);
                    }
                }
            } else {
                installmentsField.disabled = true;
                if (chargeValue > 0) {
                    addDisabledInstallmentOption(`Não é possível parcelar abaixo de ${formatCurrency(MIN_VALUE_FOR_INSTALLMENTS)}`);
                }
            }
        } else {
            installmentsRow?.style.setProperty('display', 'none');
            installmentsField.required = false;
            installmentsField.disabled = true;
        }
    }
    
    function addInstallmentOption(qtd, value) {
        const option = document.createElement('option');
        option.value = qtd;
        option.textContent = qtd === 1
            ? `À vista (${formatCurrency(value)})`
            : `Em até ${qtd}x de ${formatCurrency(value)}`;
        installmentsField.appendChild(option);
    }
    function addDisabledInstallmentOption(message) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = message;
        installmentsField.appendChild(option);
    }


    // VISTA/PARCELA APARECE
    function toggleFieldsByPaymentOption() {
        const selectedOption = document.querySelector('#payment_option_group input[type="radio"]:checked')?.value;
        const subscriptionForm = document.getElementById('subscription-form');
        const defaultFields = document.getElementById('installment-form');
    
        if (selectedOption === 'ASSINATURA') {
            subscriptionForm?.classList.remove('hidden');
            defaultFields?.classList.add('hidden');
        } else {
            subscriptionForm?.classList.add('hidden');
            defaultFields?.classList.remove('hidden');
        }
    }

    function styleRadioButtons() {
        paymentOptionGroup?.querySelectorAll('input[type="radio"]').forEach(radio => {
            const label = radio.parentElement;
            if (label) {
                if (radio.checked) {
                    label.classList.remove('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
                    label.classList.add('bg-blue-600', 'text-white', 'shadow-md');
                } else {
                    label.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                    label.classList.add('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300');
                }
            }
            radio.addEventListener('change', () => {
                toggleFieldsByPaymentOption()
                styleRadioButtons();
                updateInstallmentsOptions();
            })
        });
    }

    
    //errors 
    function showError(element) {
        element.classList.add('border-red-500');
        element.reportValidity?.();
    }
    function hideError(element) {
        if (!element || !element.classList) return;
        element.classList.remove('border-red-500');
        element.setCustomValidity?.('');
    }

    //validates form:
    function validateTypeCharge(){
        const selectedValue = chargeTypeField.value;
    
        if(!selectedValue) {
            chargeTypeField.reportValidity?.();
            showError(chargeTypeField);
            return false;
        } else{
            hideError(chargeTypeField);
            return true;
        }
    }
    function validateChargeValue(){
        const chargeValue = getChargeValue();
        console.log("Chargue value: ", chargeValue);
        if(chargeValue <= 0){
            showError(chargeValueInput);
            return false
        } 
        hideError(chargeValueInput);
        return true;
    }
    function validateInstallments() {
        const chargeValue = getChargeValue();
        const dueDateField = document.getElementById('charge_type_form_dueDate');
        const selectedPaymentOption = document.querySelector('#payment_option_group input[type="radio"]:checked')?.value;
        const installmentsValue = parseInt(installmentsField?.value);
    
        if (selectedPaymentOption !== 'A_VISTA_PARCELADO') return true;
    
        if (!installmentsValue || installmentsValue <= 0) {
            showError(installmentsField);
            return false;
        }else {
            hideError(installmentsField)
        }
        if (chargeValue < 20 && installmentsValue > 1) {
            showError(installmentsField);
            return false;
        } else {
            hideError(installmentsField)
        }
        if (!dueDateField.value) {
            showError(dueDateField);
            return false;
        } else {
            hideError(dueDateField)
        }
        return true;
    }
    function validateSubscriptionFields(){
        const selectedPaymentOption = document.querySelector('#payment_option_group input[type="radio"]:checked')?.value;
        
        if (selectedPaymentOption !== 'ASSINATURA') return true;

        const frequencyField = document.getElementById('charge_type_form_subscriptionFrequency');
        const dueDateAssField = document.getElementById('charge_type_form_dueDateAss');

        let valid = true;

        if (!frequencyField.value) {
            showError(frequencyField);
            valid = false;
        }
        if (!dueDateAssField.value) {
            showError(dueDateAssField);
            valid = false;
        }
        
        return valid;
    }
    
    //validate main
    function validateChargeStep1() {
        let isValid = true;
        
        if (!validateTypeCharge())         isValid = false;
        if (!validateChargeValue())        isValid = false;
        
        const selectedPaymentOption = getSelectedPaymentOption();

        if(selectedPaymentOption === "A_VISTA_PARCELADO")   if (!validateInstallments())       isValid = false;
        if(selectedPaymentOption === "ASSINATURA")          if (!validateSubscriptionFields()) isValid = false;
        
        return isValid;
    }

    const nextStep = document.getElementById('next-step-1');
    nextStep.addEventListener('click', () => {
        if (validateChargeStep1()) {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
            updateStepIndicator(2);
        }
    });
    prevStep2Button?.addEventListener('click', () => {
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-1').classList.remove('hidden');
        updateStepIndicator(1);
    });
    
    function updateStepIndicator(step) {
        const indicator = document.getElementById('step-indicator');
        if (indicator) {
            indicator.textContent = `Passo ${step} de 2`;
        }
    }
        
    let intervalIdDois = setInterval(() => {
        if (window.imaskInstance) {
            window.imaskInstance.on('accept', updateInstallmentsOptions);
            window.imaskInstance.on('complete', updateInstallmentsOptions);
            clearInterval(intervalIdDois);
    
            updateInstallmentsOptions(); 
        }
    }, 50);

    styleRadioButtons();
});