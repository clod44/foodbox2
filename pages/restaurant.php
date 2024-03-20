<div class="container p-4">
    <div class="px-3 d-flex gap-5">
        <img src="./media/sample.jpg" class="rounded shadow" style="height:10rem;">
        <div class="h-100">
            <div class="d-flex justify-content-between align-items-center flex-nowrap">
                <h2 class="fw-bold text-primary m-0">McDonald's</h2>
                <!--favorite button-->

                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                    <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                    <label class="btn btn-outline-primary pb-1" for="btncheck1"><i class="bi bi-heart fs-3"></i></label>
                </div>

            </div>
            <p class="lead fs-6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati pariatur ipsum unde
                adipisci, minus
                quo error perferendis facere hic qui aut eaque deserunt, quam nostrum dignissimos atque nesciunt,
            </p>
            <p class="fw-bold">4.2/5⭐(+3000)</p>
        </div>
    </div>

    <!--restaurant menus-->
    <ul class="nav nav-tabs mb-3 sticky-top bg-light" role="tablist">
        <!--menus-->
        <?php
        for ($i = 0; $i < 5; $i++) { ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="#menu_<?= $i ?>">Menu
                    <?= $i ?>
                </a>
            </li>
            <?php
        } ?>
    </ul>

    <button id="openModalBtn" class="btn btn-success btn-sm">test modal</button>
    <script>
        // Event listener for the button to trigger dynamic modal
        $('#openModalBtn').click(function () {
            // Example data for the modal content
            const modalData = [
                new ModalImage("./media/sample.jpg"),
                new ModalTitle("Your Wishes:"),
                new ModalInput("biggest wish:", "mywish1"),
                new ModalInput("best wish:", "mywish2"),
                new ModalTitle("Drink?:"),
                new ModalRadio("CocaCola", "drink", 1, 10, true),
                new ModalRadio("Pepsi", "drink", 2, 15, true),
                new ModalTitle("Extras?:"),
                new ModalCheckbox("Tomato", "extras", 12),
                new ModalCheckbox("Salad", "extras", 0),
                new ModalCheckbox("Melon", "extras", 5),
                new ModalTitle("Final:"),
                new ModalCheckbox("Agree to terms", "terms", 0),
            ];

            // Create an instance of ModalCreator
            const modal = new ModalCreator("Example Modal", modalData, "Submit");

            // Show the modal
            modal.show();
        });

    </script>
    <div class="row">
        <!--restaurat menu content-->
        <div class="col d-flex flex-column gap-3">
            <?php
            for ($i = 0; $i < 5; $i++) { ?>
                <div id="menu_<?= $i ?>">
                    <h3 class="mb-3">🍔 Menu
                        <?= $i ?>
                    </h3>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        for ($j = 0; $j < 10; $j++) {
                            ?>
                            <div class="hover-scale m-0 p-3 border border-primary rounded shadow overflow-hidden"
                                style="height:8rem; width:20rem;cursor:pointer;">
                                <div class="d-flex align-items-center justify-content-between w-100 h-100 m-0 p-0">
                                    <div class="d-flex flex-column align-items-start justify-content-between m-0 p-0">
                                        <p class="m-0 p-0 fw-bold lead">Label</p>
                                        <p class="text-break m-0 p-0 fs-7 small">Lorem ipsum dolor sit amet
                                            consectetur adiro ratione rerum, pariatur sint alias aliquid.</p>
                                        <span class="badge text-bg-warning fw-bold">12.00$</span>
                                    </div>
                                    <img src="./media/sample.jpg" class="rounded" style="width:5rem;">
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </div>
                <hr class="border-primary">
                <?php
            } ?>

        </div>
        <!--your food box-->
        <div class="col-4 m-0 p-0">
            <div class="w-100 border border-primary rounded shadow p-4">
                <h3 class="m-0 p-0 text-center mb-2">📦 Your Box 📦</h3>
                <hr class="p-0 m-2 border-primary">
                <div class="rounded w-100 mb-2" style="height:20rem;overflow-x:hidden; overflow-y:auto;">
                    <?php
                    for ($i = 0; $i < 3; $i++) {
                        ?>
                        <div class="d-flex flex-nowrap p-1 gap-2 align-items-start">
                            <img src="./media/sample.jpg" class="rounded shadow" style="height:3rem;">
                            <div class="d-flex flex-column flex-grow-1"> <!-- Added flex-grow-1 class -->
                                <div class="d-flex justify-content-between align-items-start">
                                    <p class="m-0 p-0 fs-6 fw-bold">McChicken RedFlaming</p>
                                    <button class="btn btn-lg btn-outline m-0 p-0 fs-2">⚙️</button>
                                </div>
                                <ul class="m-0 mb-2">
                                    <?php
                                    for ($j = 0; $j < 3; $j++) {
                                        ?>
                                        <li>
                                            <p class="fs-7 m-0">Something extra heooooooo</p>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" id="basic-addon1">$69.00 x</span>
                                    <input type="number" class="form-control" placeholder="amount" value="1">
                                </div>
                            </div>
                        </div>
                        <hr class="border-primary m-0 p-0 my-2">
                    <?php } ?>
                </div>
                <div class="d-flex flex-nowrap justify-content-between align-items-center mb-3">
                    <p class="m-0 p-0">Total:</p>
                    <p class="m-0 p-0 fw-bold">$132.00</p>
                </div>
                <button class="btn btn-lg w-100 btn-primary btn-shadow">
                    Confirm the Box ✅
                </button>
            </div>
        </div>
    </div>

</div>