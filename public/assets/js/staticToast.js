let staticToastCounter = 0;
const staticToast = ({msg, icon, severity, duration} = {}) => {
    let toastTheam = {};
    let toastContainer = document.querySelector('#toastContainer > div');
    if(toastContainer == null){
        toastTheam = {
            'style': 'position: fixed; top: 20px; right: 20px; z-index: 9999; width: auto; min-width: 240px; max-width: 320px;',
            'default': 'rounded-md border',
            'primary': 'border-gray-300 bg-gray-50 text-gray-600',
            'secondary': 'border-slate-300 bg-slate-50 text-slate-600',
            'success': 'border-green-300 bg-green-50 text-green-600',
            'error': 'border-red-300 bg-red-50 text-red-600',
            'info': 'border-blue-300 bg-blue-50 text-blue-600',
            'warn': 'border-yellow-300 bg-yellow-50 text-yellow-600',
            'contrast': 'border-gray-100 bg-gray-900 text-gray-100',
        }
        document.querySelector('body').insertAdjacentHTML('beforeend', `<div id="createdToastContainer"></div>`);
        toastContainer = document.querySelector('#createdToastContainer');
        toastContainer.style = toastTheam['style'];
    };
    let toast_id = staticToastCounter++;
    let template = `
        <div class="${toastTheam['default']} ${toastTheam[severity]} p-toast-message p-toast-message-${severity ?? 'contrast'}" data-static-toast="${toast_id}">
            <div class="flex items-center justify-between px-3 py-3">
                <div class="flex gap-2">
                    <i class="pi ${icon ?? 'pi-check'} mt-1"></i>
                    <span>${msg ?? 'Toast Message!'}</span>
                </div>
                <i class="pi pi-times-circle cursor-pointer ms-5" onclick="staticToastRemove(${toast_id})"></i>
            </div>
        </div>
    `;
    toastContainer.insertAdjacentHTML('beforeend', template);
    setTimeout(() => {
        staticToastRemove(toast_id);
    }, duration ?? 10000);
};
const staticToastRemove = (toast_id) => {
    let ele = document.querySelector(`[data-static-toast="${toast_id}"]`);
    ele.classList.add('animation-fade-out');
    setTimeout(() => {
        ele.remove();
    }, 500);
};