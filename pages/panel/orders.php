<!-- a control panel page for orders which teh restaurant account will be able to see -->

<div class="container p-4">
    <form class="mb-3" method="get">
        <div class="input-group">
            <input type="text" class="form-control text-center" name="search" placeholder="Search by order ID"
                aria-label="Search orders" value="<?php if (isset($_GET['search']))
                    echo $_GET['search']; ?>">
            <button class="btn btn-primary hover-scale" type="submit">Filter 🔎</button>
        </div>
        <div class="input-group input-group-sm">
            <select class="form-select text-center" name="search">
                <option value="OrderID" <?php if (isset($_GET['search']) && $_GET['search'] == "OrderID")
                    echo "selected"; ?>>OrderID🧾</option>
                <option value="UserID" <?php if (isset($_GET['search']) && $_GET['search'] == "UserID")
                    echo "selected"; ?>>UserID👤</option>
                <option value="Date" <?php if (isset($_GET['search']) && $_GET['search'] == "Date")
                    echo "selected"; ?>>Date📅</option>
            </select>
            <select class="form-select text-center" name="sort">
                <option value="DESC" <?php if (isset($_GET['sort']) && $_GET['sort'] == "DESC")
                    echo "selected"; ?>>DESC⬇️
                </option>
                <option value="ASC" <?php if (isset($_GET['sort']) && $_GET['sort'] == "ASC")
                    echo "selected"; ?>>ASC⬆️
                </option>
            </select>
        </div>
    </form>
    <div>
        <div class="d-flex flex-column gap-4 rounded border border-primary p-1">
            <div class="d-flex flex-nowrap p-1 gap-2 align-items-start border border-primary rounded text-bg-secondary">
                <img src="./media/sample.jpg" class="rounded shadow" style="height:3rem;">
                <div class="d-flex flex-column flex-grow-1"> <!-- Added flex-grow-1 class -->
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    📅12.03.2024 - 14:36:27 🧾189 👤446
                                </button>
                                <div class="btn-group" role="group" aria-label="Order status">
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1"
                                        autocomplete="off">
                                    <label class="btn btn-outline-danger" for="btnradio1">❌Rejected</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2"
                                        autocomplete="off" checked>
                                    <label class="btn btn-outline-warning" for="btnradio2">⌛Pending</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3"
                                        autocomplete="off">
                                    <label class="btn btn-outline-warning" for="btnradio3">🍳Preparing</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4"
                                        autocomplete="off">
                                    <label class="btn btn-outline-warning" for="btnradio4">🛵Delivering</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio5"
                                        autocomplete="off">
                                    <label class="btn btn-outline-success" for="btnradio5">✅Delivered</label>
                                </div>

                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="m-0">
                                        <?php
                                        for ($j = 0; $j < 3; $j++) {
                                            ?>
                                            <li>
                                                <p class="fs-7 m-0">Something extra heooooooo</p>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="basic-addon1">$69.00 x</span>
                        <input type="number" readonly class="form-control" placeholder="amount" value="1">
                    </div>
                </div>
            </div>
            <hr>
        </div>

    </div>
</div>