// resources/js/checkout.js

class CheckoutHandler {
    constructor() {
        this.isProcessing = false;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupQuantityCalculator();
        this.checkPaymentMode();
    }

    setupEventListeners() {
        const payButton = document.getElementById("pay-button");
        if (payButton) {
            payButton.addEventListener("click", () => {
                this.processPayment();
            });
        }

        // Enter key support untuk form
        const form = document.getElementById("checkout-form");
        if (form) {
            form.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    this.processPayment();
                }
            });
        }
    }

    setupQuantityCalculator() {
        const quantityInput = document.getElementById("quantity");
        const totalPriceEl = document.getElementById("total-price");

        if (!quantityInput || !totalPriceEl) return;

        const productPrice = parseInt(
            document.getElementById("product-price").dataset.price
        );
        const maxStock = parseInt(quantityInput.max) || 999;

        quantityInput.addEventListener("input", function () {
            let qty = parseInt(this.value);

            // Validasi input
            if (isNaN(qty) || qty < 1) {
                qty = 1;
            }
            if (qty > maxStock) {
                qty = maxStock;
                this.value = maxStock;
            }

            this.value = qty;

            // Update total price
            const total = qty * productPrice;
            totalPriceEl.textContent = "Rp " + total.toLocaleString("id-ID");
        });

        // Trigger initial calculation
        quantityInput.dispatchEvent(new Event("input"));
    }

    checkPaymentMode() {
        console.log("🔍 Checking payment mode...");

        if (window.paymentMode) {
            const currentMode = window.paymentMode.getMode();
            console.log("🎛️ Current payment mode:", currentMode);

            // Update UI jika ada elemen untuk menampilkan mode
            this.updatePaymentModeDisplay(currentMode);
        } else {
            console.warn("⚠️ PaymentMode class not available");
        }
    }

    updatePaymentModeDisplay(mode) {
        // Update tampilan mode pembayaran jika ada elemennya
        const modeDisplay = document.getElementById("paymentModeDisplay");
        if (modeDisplay) {
            modeDisplay.textContent =
                mode === "auto_success" ? "Auto Success" : "Real Payment";
            modeDisplay.className =
                mode === "auto_success"
                    ? "text-yellow-600 font-bold"
                    : "text-blue-600 font-bold";
        }
    }

    async processPayment() {
        if (this.isProcessing) {
            console.log("⏳ Payment already processing, please wait...");
            return;
        }

        console.log("🔄 Starting payment process...");

        // Validasi form
        if (!this.validateForm()) {
            console.log("❌ Form validation failed");
            return;
        }

        this.isProcessing = true;
        this.showLoading();

        try {
            const formData = this.getFormData();

            console.log("📤 Sending payment request with data:", formData);

            const response = await fetch("/transactions/pay", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();
            console.log("📦 Server response received:", data);

            if (!response.ok) {
                throw new Error(
                    data.message || `Server error: ${response.status}`
                );
            }

            if (data.success) {
                console.log("✅ Payment request successful");

                if (data.auto_success) {
                    // AUTO SUCCESS MODE - REDIRECT LANGSUNG
                    console.log(
                        "🚀 Auto success mode - Redirecting to:",
                        data.redirect_url
                    );

                    // Tambahkan delay kecil untuk memastikan loading terlihat
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 500);
                } else if (data.snapToken) {
                    // REAL PAYMENT MODE - BUKA SNAP MIDTRANS
                    console.log("💰 Real payment mode - Opening Snap dialog");
                    this.openSnapPayment(data.snapToken, data.transaction_code);
                } else {
                    throw new Error("Invalid response format from server");
                }
            } else {
                throw new Error(data.message || "Payment request failed");
            }
        } catch (error) {
            console.error("❌ Payment process error:", error);
            this.showError("Terjadi kesalahan: " + error.message);
        } finally {
            this.isProcessing = false;
            this.hideLoading();
        }
    }

    openSnapPayment(snapToken, transactionCode) {
        console.log("🔗 Preparing to open Snap payment...");

        if (typeof window.snap === "undefined") {
            console.error("❌ Snap.js not loaded");
            this.showError(
                "Sistem pembayaran tidak tersedia. Silakan refresh halaman dan coba lagi."
            );
            return;
        }

        if (!snapToken) {
            console.error("❌ Snap token is empty");
            this.showError("Token pembayaran tidak valid.");
            return;
        }

        console.log(
            "🎯 Opening Snap payment dialog with token:",
            snapToken.substring(0, 20) + "..."
        );

        try {
            window.snap.pay(snapToken, {
                onSuccess: (result) => {
                    console.log("✅ Payment successful:", result);
                    this.redirectToFinish(transactionCode, "settlement");
                },
                onPending: (result) => {
                    console.log("⏳ Payment pending:", result);
                    this.redirectToFinish(transactionCode, "pending");
                },
                onError: (result) => {
                    console.log("❌ Payment failed:", result);
                    this.showError(
                        "Pembayaran gagal: " +
                            (result.status_message || "Terjadi kesalahan")
                    );
                    this.redirectToFinish(transactionCode, "error");
                },
                onClose: () => {
                    console.log("🚪 Payment dialog closed by user");
                    this.showInfo(
                        "Pembayaran dibatalkan. Anda dapat mencoba lagi nanti."
                    );
                },
            });
        } catch (snapError) {
            console.error("💥 Snap payment error:", snapError);
            this.showError(
                "Gagal membuka dialog pembayaran: " + snapError.message
            );
        }
    }

    redirectToFinish(transactionCode, status) {
        const finishUrl = `/transactions/finish?order_id=${transactionCode}&transaction_status=${status}`;
        console.log("🔀 Redirecting to finish page:", finishUrl);
        window.location.href = finishUrl;
    }

    validateForm() {
        console.log("🔍 Validating form...");

        const requiredFields = [
            { id: "recipient_name", name: "Nama Penerima" },
            { id: "phone", name: "Nomor Telepon" },
            { id: "shipping_address", name: "Alamat Pengiriman" },
        ];

        // Validasi field required
        for (const field of requiredFields) {
            const fieldElement = document.getElementById(field.id);
            if (!fieldElement || !fieldElement.value.trim()) {
                this.showError(`${field.name} harus diisi`);
                if (fieldElement) fieldElement.focus();
                return false;
            }
        }

        // Validasi quantity
        const quantityInput = document.getElementById("quantity");
        if (!quantityInput) {
            this.showError("Field quantity tidak ditemukan");
            return false;
        }

        const quantity = parseInt(quantityInput.value);
        const maxStock = parseInt(quantityInput.max) || 999;

        if (isNaN(quantity) || quantity < 1) {
            this.showError("Jumlah harus lebih dari 0");
            quantityInput.focus();
            return false;
        }

        if (quantity > maxStock) {
            this.showError(`Jumlah melebihi stok tersedia. Stok: ${maxStock}`);
            quantityInput.focus();
            return false;
        }

        console.log("✅ Form validation passed");
        return true;
    }

    getFormData() {
        console.log("📝 Preparing form data...");

        // Dapatkan payment mode
        let paymentMode = "real";
        if (
            window.paymentMode &&
            typeof window.paymentMode.getMode === "function"
        ) {
            paymentMode = window.paymentMode.getMode();
            console.log("🎛️ Using payment mode from class:", paymentMode);
        } else {
            console.warn(
                "⚠️ PaymentMode class not available, using default: real"
            );
        }

        const data = {
            encryptedId: document.querySelector("input[name=encryptedId]")
                .value,
            recipient_name: document
                .getElementById("recipient_name")
                .value.trim(),
            phone: document.getElementById("phone").value.trim(),
            shipping_address: document
                .getElementById("shipping_address")
                .value.trim(),
            quantity: parseInt(document.getElementById("quantity").value),
            notes: document.getElementById("notes").value.trim(),
            payment_mode: paymentMode,
        };

        console.log("📋 Form data prepared:", data);
        return data;
    }

    getCsrfToken() {
        const token =
            document.querySelector("input[name=_token]")?.value ||
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

        if (!token) {
            console.warn("⚠️ CSRF token not found");
        }

        return token;
    }

    showLoading() {
        console.log("⏳ Showing loading state...");

        // Show spinner
        const spinner = document.getElementById("loadingSpinner");
        if (spinner) {
            spinner.classList.remove("hidden");
        }

        // Disable pay button
        const payButton = document.getElementById("pay-button");
        if (payButton) {
            payButton.disabled = true;
            payButton.textContent = "Memproses Pembayaran...";
            payButton.classList.add("opacity-50", "cursor-not-allowed");
        }

        // Disable form inputs
        this.disableForm(true);
    }

    hideLoading() {
        console.log("✅ Hiding loading state...");

        // Hide spinner
        const spinner = document.getElementById("loadingSpinner");
        if (spinner) {
            spinner.classList.add("hidden");
        }

        // Enable pay button
        const payButton = document.getElementById("pay-button");
        if (payButton) {
            payButton.disabled = false;
            payButton.textContent = "Lanjutkan ke Pembayaran";
            payButton.classList.remove("opacity-50", "cursor-not-allowed");
        }

        // Enable form inputs
        this.disableForm(false);
    }

    disableForm(disabled) {
        const form = document.getElementById("checkout-form");
        if (!form) return;

        const inputs = form.querySelectorAll("input, textarea, button, select");
        inputs.forEach((input) => {
            if (input.id !== "pay-button") {
                // Jangan disable pay button lagi
                input.disabled = disabled;
            }
        });
    }

    showError(message) {
        console.error("❌ Error:", message);

        // Gunakan alert sederhana atau custom notification
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: message,
                confirmButtonColor: "#ef4444",
            });
        } else {
            alert("❌ " + message);
        }
    }

    showInfo(message) {
        console.log("ℹ️ Info:", message);

        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "info",
                title: "Info",
                text: message,
                confirmButtonColor: "#3b82f6",
            });
        } else {
            alert("ℹ️ " + message);
        }
    }

    showSuccess(message) {
        console.log("✅ Success:", message);

        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: message,
                confirmButtonColor: "#10b981",
            });
        } else {
            alert("✅ " + message);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    console.log("🚀 Initializing CheckoutHandler...");

    // Check if we're on checkout page
    if (document.getElementById("pay-button")) {
        window.checkoutHandler = new CheckoutHandler();
        console.log("✅ CheckoutHandler initialized successfully");

        // Debug info
        console.log("🔧 Debug Information:");
        console.log(
            "- PaymentMode available:",
            typeof window.paymentMode !== "undefined"
        );
        console.log("- Snap available:", typeof window.snap !== "undefined");

        if (window.paymentMode) {
            console.log(
                "- Current payment mode:",
                window.paymentMode.getMode()
            );
        }
    } else {
        console.log("ℹ️ Not on checkout page, CheckoutHandler not initialized");
    }
});

// Export for module system
if (typeof module !== "undefined" && module.exports) {
    module.exports = CheckoutHandler;
}
