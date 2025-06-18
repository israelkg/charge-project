
document.addEventListener('DOMContentLoaded', function () {
    const multiStepForm = document.getElementById('multi-step-form');
    const stepIndicator = document.getElementById('step-indicator');
    const formSteps = document.querySelectorAll('.form-step');

    const chargeValueInput = document.getElementById('charge_form_type_value');
    const paymentOptionGroup = document.getElementById('payment_option_group');
    const installmentsRow = document.getElementById('installments_row');
    const installmentsField = document.getElementById('charge_type_form_installments');
    const nextStep1Button = document.getElementById('next-step-1');

    const clientNameInput = document.getElementById(multiStepForm?.elements['charge_type_form_clientName']?.id);
    const clientEmailInput = document.getElementById(multiStepForm?.elements['charge_type_form_clientEmail']?.id);
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
        const value = window.imaskInstance?.unmaskedValue;
        return value ? parseFloat(value.replace(',', '.')) : 0;
    }

    function formatCurrency(value) {
        return window.formatCurrency?.(value) || value;
    }
    
    function updateInstallmentsOptions() {
        const chargeValue = getChargeValue();

        let selectedPaymentOption = '';
        if (paymentOptionGroup) {
            paymentOptionGroup.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.checked) selectedPaymentOption = radio.value;
            });
        }

        if (installmentsField) installmentsField.innerHTML = '';

        if (selectedPaymentOption === 'A_VISTA_PARCELADO') {
            installmentsRow?.style.setProperty('display', 'block');
            installmentsField.required = true;

            let optionVista = document.createElement('option');
            optionVista.value = 1;
            optionVista.textContent = `À vista (${formatCurrency(chargeValue)})`;
            installmentsField.appendChild(optionVista);

            if (chargeValue >= MIN_VALUE_FOR_INSTALLMENTS) {
                installmentsField.disabled = false;
                for (let i = 2; i <= 12; i++) {
                    const installmentValue = chargeValue / i;
                    if (installmentValue >= 5) {
                        let option = document.createElement('option');
                        option.value = i;
                        option.textContent = `Em até ${i}x de ${formatCurrency(installmentValue)}`;
                        installmentsField.appendChild(option);
                    }
                }
            } else {
                installmentsField.disabled = true;
                if (chargeValue > 0) {
                    let noInstallmentOption = document.createElement('option');
                    noInstallmentOption.value = '';
                    noInstallmentOption.textContent = `Não é possível parcelar abaixo de ${formatCurrency(MIN_VALUE_FOR_INSTALLMENTS)}`;
                    installmentsField.appendChild(noInstallmentOption);
                }
            }
        } else {
            installmentsRow?.style.setProperty('display', 'none');
            installmentsField.required = false;
            installmentsField.disabled = true;
        }
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

    function showStep(stepNum) {
        formSteps.forEach((step, index) => {
            step.classList.toggle('hidden', index !== stepNum);
        });
        updateStepIndicator();
    }

    function updateStepIndicator() {
        if (stepIndicator) stepIndicator.textContent = `Passo ${currentStep + 1} de ${formSteps.length}`;
    } 
    

    function validateCurrentStep() {
        let currentStepElement = formSteps[currentStep];
        let isValid = true;

        currentStepElement.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.required && input.offsetParent !== null && !input.checkValidity()) {
                isValid = false;
                input.classList.add('border-red-500');
                input.reportValidity();
            } else {
                input.classList.remove('border-red-500');
            }
        });

        if (currentStep === 0) {
            const chargeValue = getChargeValue();
            if (chargeValue <= 0) {
                isValid = false;
                chargeValueInput.classList.add('border-red-500');
                chargeValueInput.reportValidity();
            } else {
                chargeValueInput.classList.remove('border-red-500');
            }

            let selectedPaymentOption = '';
            paymentOptionGroup.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.checked) selectedPaymentOption = radio.value;
            });

            if (selectedPaymentOption === 'A_VISTA_PARCELADO') {
                if (!installmentsField.value || installmentsField.value === '0') {
                    isValid = false;
                    installmentsField.classList.add('border-red-500');
                    installmentsField.reportValidity();
                } else {
                    installmentsField.classList.remove('border-red-500');
                }

                if (chargeValue < MIN_VALUE_FOR_INSTALLMENTS && installmentsField.value !== '1') {
                    isValid = false;
                    installmentsField.classList.add('border-red-500');
                    installmentsField.setCustomValidity(`Não é possível parcelar valores abaixo de ${formatCurrency(MIN_VALUE_FOR_INSTALLMENTS)}. Selecione 'À vista'.`);
                    installmentsField.reportValidity();
                } else {
                    installmentsField.setCustomValidity('');
                }
            }
        }
        return isValid;
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < formSteps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
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

    nextStep1Button?.addEventListener('click', nextStep);
    prevStep2Button?.addEventListener('click', prevStep);

    submitFormButton?.addEventListener('click', function (event) {
        if (!validateCurrentStep()) event.preventDefault();
    });

    showStep(currentStep);
    styleRadioButtons();
});