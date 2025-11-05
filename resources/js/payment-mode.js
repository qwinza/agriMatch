// resources/js/payment-mode.js

class PaymentMode {
    constructor() {
        this.autoSuccess = localStorage.getItem("autoSuccessMode") === "true";
        this.init();
    }

    init() {
        this.updateUI();
        this.setupEventListeners();
    }

    updateUI() {
        const indicators = document.querySelectorAll(".payment-mode-indicator");
        indicators.forEach((indicator) => {
            if (this.autoSuccess) {
                indicator.innerHTML =
                    '<i class="fas fa-bolt text-yellow-500 mr-1"></i>Auto Success';
                indicator.className =
                    "payment-mode-indicator inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800";
            } else {
                indicator.innerHTML =
                    '<i class="fas fa-globe text-blue-500 mr-1"></i>Real Payment';
                indicator.className =
                    "payment-mode-indicator inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800";
            }
        });
    }

    setupEventListeners() {
        // Listen for form submissions to add payment mode parameter
        document.addEventListener("submit", (e) => {
            const form = e.target;
            if (
                form.method.toLowerCase() === "post" &&
                (form.action.includes("/transactions/pay") ||
                    form.action.includes("/transactions/pay-auto-success"))
            ) {
                this.addPaymentModeToForm(form);
            }
        });

        // Listen for AJAX requests
        this.interceptAjaxRequests();
    }

    addPaymentModeToForm(form) {
        let existingInput = form.querySelector('input[name="payment_mode"]');
        if (!existingInput) {
            existingInput = document.createElement("input");
            existingInput.type = "hidden";
            existingInput.name = "payment_mode";
            form.appendChild(existingInput);
        }
        existingInput.value = this.autoSuccess ? "auto_success" : "real";
    }

    interceptAjaxRequests() {
        const originalFetch = window.fetch;
        const self = this;

        window.fetch = (...args) => {
            if (
                typeof args[0] === "string" &&
                (args[0].includes("/transactions/pay") ||
                    args[0].includes("/transactions/pay-auto-success"))
            ) {
                if (args[1] && args[1].body instanceof FormData) {
                    args[1].body.append(
                        "payment_mode",
                        self.autoSuccess ? "auto_success" : "real"
                    );
                } else if (args[1] && typeof args[1].body === "string") {
                    try {
                        const body = JSON.parse(args[1].body);
                        body.payment_mode = self.autoSuccess
                            ? "auto_success"
                            : "real";
                        args[1].body = JSON.stringify(body);
                    } catch (e) {
                        console.log(
                            "Could not parse request body for payment mode"
                        );
                    }
                }
            }
            return originalFetch(...args);
        };

        // Juga intercept XMLHttpRequest
        const originalXHROpen = XMLHttpRequest.prototype.open;
        const originalXHRSend = XMLHttpRequest.prototype.send;

        XMLHttpRequest.prototype.open = function (method, url, ...rest) {
            this._url = url;
            return originalXHROpen.apply(this, [method, url, ...rest]);
        };

        XMLHttpRequest.prototype.send = function (data) {
            if (
                this._url &&
                (this._url.includes("/transactions/pay") ||
                    this._url.includes("/transactions/pay-auto-success"))
            ) {
                if (data instanceof FormData) {
                    data.append(
                        "payment_mode",
                        self.autoSuccess ? "auto_success" : "real"
                    );
                } else if (typeof data === "string") {
                    try {
                        const body = JSON.parse(data);
                        body.payment_mode = self.autoSuccess
                            ? "auto_success"
                            : "real";
                        data = JSON.stringify(body);
                    } catch (e) {
                        console.log(
                            "Could not parse XHR body for payment mode"
                        );
                    }
                }
            }
            return originalXHRSend.call(this, data);
        };
    }

    toggle() {
        this.autoSuccess = !this.autoSuccess;
        localStorage.setItem("autoSuccessMode", this.autoSuccess);
        this.updateUI();
        this.showNotification();
        return this.autoSuccess;
    }

    showNotification() {
        const message = this.autoSuccess
            ? "Auto Success Mode: ON - Pembayaran akan otomatis berhasil"
            : "Auto Success Mode: OFF - Menggunakan pembayaran real Midtrans";

        const type = this.autoSuccess ? "warning" : "info";

        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg border transform transition-transform duration-300 ${
            this.autoSuccess
                ? "bg-yellow-50 border-yellow-200 text-yellow-800"
                : "bg-blue-50 border-blue-200 text-blue-800"
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${
                    this.autoSuccess ? "bolt" : "globe"
                } mr-3"></i>
                <span class="font-medium">${message}</span>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = "translateX(100%)";
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Method untuk mendapatkan status current mode
    getMode() {
        return this.autoSuccess ? "auto_success" : "real";
    }

    // Method untuk set mode secara manual
    setMode(mode) {
        this.autoSuccess = mode === "auto_success";
        localStorage.setItem("autoSuccessMode", this.autoSuccess);
        this.updateUI();
        this.showNotification();
    }
}

// Initialize payment mode ketika DOM siap
document.addEventListener("DOMContentLoaded", function () {
    window.paymentMode = new PaymentMode();

    // Handle toggle button jika ada
    const toggleButton = document.getElementById("togglePaymentMode");
    if (toggleButton) {
        toggleButton.addEventListener("click", function () {
            window.paymentMode.toggle();

            // Update button text
            const modeText = document.getElementById("modeText");
            if (modeText) {
                modeText.textContent = `Auto: ${
                    window.paymentMode.autoSuccess ? "ON" : "OFF"
                }`;
            }

            // Update button style
            toggleButton.className = window.paymentMode.autoSuccess
                ? "bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors"
                : "bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors";
        });
    }
});

// Export untuk module system (jika menggunakan Vite/mix)
if (typeof module !== "undefined" && module.exports) {
    module.exports = PaymentMode;
}
