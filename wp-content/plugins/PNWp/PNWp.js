function pnwpStart(id, value) {
    console.log("Updating cart status:", id, value);

    jQuery.ajax({
        type: "POST",
        url: cartStatusAjax.ajaxurl,
        data: {
            action: "update_cart_status",
            nonce: cartStatusAjax.nonce,
            cart_id: id,
            status: value,
        },
        success: function (response) {
            console.log("Server Response:", response);
            if (response.success) {
                alert(response.data.message);
            } else {
                alert("Error: " + response.data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        },
    });
}

jQuery(document).ready(function ($) {
    window.pnwpStart = pnwpStart; // Ensure it's globally accessible
});
