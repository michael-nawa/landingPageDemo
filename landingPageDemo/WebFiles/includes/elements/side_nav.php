<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon">
                        <i class="icofont-dashboard-web"></i>
                    </div>
                    Dashboard
                </a>

                <!-- meta data -->
                <div>
                    <div class="sb-sidenav-menu-heading">System Data</div>
                    <a class="nav-link" href="products.php">
                        <div class="sb-nav-link-icon">
                            <i class=" icofont-phone"></i>
                        </div>
                        Products
                    </a>
                    <a class="nav-link" href="category.php">
                        <div class="sb-nav-link-icon">
                            <i class=" icofont-bag"></i>
                        </div>
                        Categories
                    </a>
                </div>

                <div>
                    <div class="sb-sidenav-menu-heading">User Data</div>
                    <a class="nav-link" href="users.php">
                        <div class="sb-nav-link-icon">
                            <i class=" icofont-users"></i>
                        </div>
                        Users
                    </a>
                    <a class="nav-link" href="transactions.php">
                        <div class="sb-nav-link-icon">
                            <i class=" icofont-money"></i>
                        </div>
                        Transactions
                    </a>
                </div>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <p id="UserName"></p>
        </div>
    </nav>
</div>

<script>
    document.getElementById(`UserName`).innerHTML = getCookie("UserName");;
</script>