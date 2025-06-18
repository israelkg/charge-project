window.addEventListener('load', function() {
    const chargeValueInput = document.getElementById('charge_type_form_value');
    const chargeDateInput = document.getElementById('charge_type_form_dueDate');
    const chargeDateInputAss = document.getElementById('charge_type_form_dueDateAss');

    if (!chargeValueInput) {
        console.error("Element with ID 'charge_type_form_value' not found. Cannot initialize IMask.js or other form logic.");
        return;
    }
    if (!chargeDateInput) {
        console.error("Element with ID 'charge_type_form_dueDate' not found. Cannot initialize IMask.js or other form logic.");
        return;
    }

    const maskOptions = {
        mask: Number,
        scale: 2,
        signed: false,
        thousandsSeparator: '.',
        padFractionalZeros: true,
        normalizeZeros: true,
        radix: ',',
        mapToRadix: ['.'],
    };
    window.imaskInstance = IMask(chargeValueInput, maskOptions);
    imaskInstance.value = '0,00'; 

    
    function applyDateMaskAndPicker(inputElement) {
        if (!inputElement) return;
    
        const dateMaskOptions = {
            mask: Date,
            pattern: '`d`/`m`/`YY`',
            lazy: true,
            blocks: {
                d: { mask: IMask.MaskedRange, from: 1, to: 31 },
                m: { mask: IMask.MaskedRange, from: 1, to: 12 },
                Y: { mask: IMask.MaskedRange, from: 1900, to: 2100 },
            },
            autofix: true,
        };
    
        const imaskInstance = IMask(inputElement, dateMaskOptions);
    
        flatpickr(inputElement, {
            dateFormat: "d/m/Y",
            locale: "pt",
            allowInput: false,
            minDate: "today",
    
            onClose: function (selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const formatted = flatpickr.formatDate(selectedDates[0], "d/m/Y");
                    imaskInstance.value = formatted;
                    inputElement.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },
    
            onOpen: function (selectedDates, dateStr, instance) {
                if (imaskInstance.value && imaskInstance.value !== '__/__/____') {
                    instance.setDate(imaskInstance.value, true);
                } else {
                    instance.setDate(instance.config.minDate || new Date(), true);
                }
            },
        });
    }
    
    applyDateMaskAndPicker(chargeDateInput);
    applyDateMaskAndPicker(chargeDateInputAss);
});