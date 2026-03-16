document.addEventListener("DOMContentLoaded", function () {

    const viewButtons = document.querySelectorAll(".view-order");

    viewButtons.forEach(button => {

        button.addEventListener("click", function (e) {

            e.preventDefault();

            const orderId = this.dataset.id;

            fetch(`/orders/details?id=${orderId}`)

            .then(response => response.json())

            .then(data => {

                alert("Order items:\n" + JSON.stringify(data));

            })

            .catch(error => {

                console.error("Error loading order details", error);

            });

        });

    });

});