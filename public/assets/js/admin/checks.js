document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".view-user-orders");

    buttons.forEach(button => {

        button.addEventListener("click", function (e) {

            e.preventDefault();

            const userId = this.dataset.id;

            fetch(`/admin/checks/user-orders?id=${userId}`)

            .then(response => response.json())

            .then(data => {

                console.log("User orders:", data);

                alert("Orders loaded. Check console.");

            })

            .catch(error => {

                console.error("Error loading orders", error);

            });

        });

    });

});