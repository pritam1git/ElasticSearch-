<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.includes.head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-theme-classic">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #search-box {
            margin: 20px;
        }

        #results {
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination button,
        .pagination button {
            margin: 0 5px;
            padding: 10px 15px;
            border: none;
            background-color: #f1f1f1;
            color: #333;
            cursor: pointer;
        }

        .pagination button:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }

        .pagination button.active {
            background-color: #0056b3;
            color: #fff;
        }

    </style>
</head>

<body>
    <!-- main-section starts -->
    <main class="wrapper">

        <!-- header start here -->
        <header id="mainHeader" class="main-header inner-header ">
            @include('frontend.includes.header')
        </header>
        <!-- header ends here -->

        <!-- main section start -->
        <main class="main-content">

            <!-- banner starts here -->
            <section
                class="banner-section d-flex justify-content-center align-items-center half-banner banner-pad pb-4 pb-lg-5 grad-bg-2">
                <div class="container position-relative">
                    <div class="row align-items-end ">
                        <div class="col-lg-8 mx-auto">
                            <div class="banner-left-section text-center mb-0">
                                <div class="main-page-title text-white pb-14">
                                    <h1>Browse Coupons</h1>
                                </div>
                                <div class="subtitle text-white">
                                    <h4 class="sub-heading">
                                        Let our experts find the best coupons and deals for you!
                                    </h4>
                                </div>  
                            </div>
                        </div>
                    </div>
            </section>

            <section class="store-details banner-pad pb-0">
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-2 p-3 mb-3 mb-xl-4">
                            <div class="categ-group d-flex">
                                        <input type="text" id="search-input" class="form-control" placeholder="Search For"
                                            aria-label="Search For" value="">
                                        <button class="btn secondary-btn" id="search-button" type="submit">
                                            <img class="img-fluid" src="{{URL::asset('assets/images/search.png')}}"
                                                alt="search-ico">
                                        </button>
                                    </div>
                                <div class="categ-group" id="filters">
                                    <div class="facets-container">
                                        <!-- Brands Facets -->
                                        <div class="facet-group">
                                            <div class="border-title mb-3 mb-xl-4 d-flex justify-content-between">
                                                <h4 class="fw-700">Brands</h4>
                                                <div class="categ_search">
                                                    <div class="search-icon" data-facet="brands" style="cursor: pointer;">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <input type="text" class="facet-search d-none" data-facet="brands"
                                                        placeholder="Search Brands...">
                                                </div>
                                            </div>
                                            <ul class="menu mb-0 badge-lst" id="filters-brands" role="group"
                                                aria-label="Brands checkbox toggle button group">
                                            </ul>
                                        </div>

                                        <!-- Categories Facets -->
                                        <div class="facet-group">
                                            <div class="border-title mb-3 mb-xl-4 d-flex justify-content-between">
                                                <h4 class="fw-700">Categories</h4>
                                                <div class="categ_search">
                                                    <div class="search-icon" data-facet="categories" style="cursor: pointer;">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <input type="text" class="facet-search d-none" data-facet="categories"
                                                        placeholder="Search Categories...">
                                                </div>
                                            </div>
                                            <ul class="menu mb-0 badge-lst" id="filters-categories" role="group"
                                                aria-label="Categories checkbox toggle button group">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="view-tabs-wrap">
                                <div class="view-tabs">
                                    <div class="btn-view-grp me-2 me-md-3">
                                    <button type="button" onclick="ViewTab('tab1', this)" class="view-btn active-view">
                                        <img class="img-fluid tab-ico tab-dark" src="{{URL::asset('assets/images/tab-dark.png')}}" alt="tabs-ico">
                                        <img class="img-fluid tab-ico tab-light" src="{{URL::asset('assets/images/tab-ico.png');}}" alt="tabs-ico">
                                    </button>
                                    <button type="button" onclick="ViewTab('tab2', this)" class="view-btn">
                                        <img class="img-fluid tab-ico tab-dark" src="{{URL::asset('assets/images/list-view-dark.png');}}" alt="tabs-ico">
                                        <img class="img-fluid tab-ico tab-light" src="{{URL::asset('assets/images/list-view.png');}}" alt="tabs-ico">
                                    </button>
                                    </div>
                                    <p class="view-txt mb-0">
                                        Found
                                        <span class=" view-txt mb-0 totalCoupon"id="totalCount">
                                        </span>
                                        coupons
                                    </p>
                                </div>
                                <div id="loader" style="display: none;margin-top: 101px;margin-bottom: 1500px;">
                                    <i  class="fa fa-spinner fa-spin" style="display: flex;justify-content: center;font-size:40px;color:#003fadc7;"></i>
                                </div>
                                <div class="top-store ">
                                    <div class="store-slide-wrap slider-wrap mb-0">
                                        <div class="swiper store-slide">
                                            <div class="swiper-wrapper" id="swiper-wrapper">

                                            </div>
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab1" class="tab-content active tab-view-content list-view  coupons-wrapper1">
                                    <div id="results">
                                    </div>   
                                </div>
                                <div id="tab2" class="tab-content tab-view-content tab-grid-view">
                                    <div class="row coupons-wrapper2 fix-coupn"id="results2">
                                    </div>
                                </div>
                                <div id="pageNumbers" class="pagination" style="display: flex;justify-content: center;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @include('frontend.includes.footer')
            <!-- main footer end -->
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@algolia/autocomplete-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch"></script>
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.24.0/dist/algoliasearch-lite.umd.js" integrity="sha256-b2n6oSgG4C1stMT/yc/ChGszs9EY/Mhs6oltEjQbFCQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.74.0/dist/instantsearch.production.min.js" integrity="sha256-1OlwSxFMcBXdQtWWvx95HkDw88ZSOde0gyii+lyOkB4=" crossorigin="anonymous"></script>
</body>

</html>
<script>
        function ViewTab(tabId, element) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active-view');
            });
            element.classList.add('active-view');
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('tab1').classList.add('active');
        });
        document.addEventListener('DOMContentLoaded', function () {
            const storeSlider = new Swiper('.store-slide', {
                slidesPerView: 2,
                spaceBetween: 20,
                observer: true,
                observeParents: true,
                breakpoints: {
                    640: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1024: {
                        slidesPerView: 4,
                    },
                    1280: {
                        slidesPerView: 4,
                    }
                },
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.store-slide .swiper-button-next',
                    prevEl: '.store-slide .swiper-button-prev',
                },
            });
        });
</script>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            let selectedBrands = [];
            let selectedCategories = [];
            const searchQuery = '';
            fetchFilteredCoupons(selectedBrands, selectedCategories, searchQuery);
            initializeCheckboxListeners();
            document.getElementById('search-button').addEventListener('click', function () {
                const searchQuery = document.getElementById('search-input').value;
                fetchFilteredCoupons(selectedBrands, selectedCategories, searchQuery);
            });
            function initializeCheckboxListeners() {
                document.querySelectorAll('input[name="brands"]').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        handleCheckboxChange(checkbox, selectedBrands);
                    });
                });
                document.querySelectorAll('input[name="categories"]').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        handleCheckboxChange(checkbox, selectedCategories);
                    });
                });
            }
            function handleCheckboxChange(checkbox) {
                const facetKey = checkbox.name;
                const value = checkbox.value;

                if (checkbox.checked) {
                    if (facetKey === 'brands') {
                        if (!selectedBrands.includes(value)) {
                            selectedBrands.push(value);
                        }
                    } else if (facetKey === 'categories') {
                        if (!selectedCategories.includes(value)) {
                            selectedCategories.push(value);
                        }
                    }
                } else {
                    if (facetKey === 'brands') {
                        const index = selectedBrands.indexOf(value);
                        if (index > -1) {
                            selectedBrands.splice(index, 1);
                        }
                    } else if (facetKey === 'categories') {
                        const index = selectedCategories.indexOf(value);
                        if (index > -1) {
                            selectedCategories.splice(index, 1);
                        }
                    }
                }

                console.log('Updated selected values:', { selectedBrands, selectedCategories });
                const searchQuery = document.getElementById('search-input').value;
                fetchFilteredCoupons(selectedBrands, selectedCategories, searchQuery);
            }
            function bindFacetCheckboxEvents() {
                $(document).off('change', '.btn-check'); // Remove previous event listeners
                $(document).on('change', '.btn-check', function() {
                    handleCheckboxChange(this);  // Pass the current checkbox element to handle the change
                });
            }

        // Function to fetch coupons based on selected brands/categories and search query
        function fetchFilteredCoupons(selectedBrands, selectedCategories, searchQuery, page = 1) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            showLoader()
            fetch('/elasticsearch/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    brands: selectedBrands,
                    categories: selectedCategories,
                    search: searchQuery,
                    page: page
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log('all data',data);
                renderFacets(data);
                renderCoupons(data.couponData, data.ip);
                renderPagination(data);  // Adjust pagination rendering
                document.querySelector('#totalCount').textContent = data.totalCount;

                // Reapply checkbox selections after filtering or pagination
                reapplyCheckboxSelections(selectedBrands, selectedCategories);
            })
            .catch(error => console.error('Error fetching coupons:', error));
        }


            function reapplyCheckboxSelections(storedBrands, storedCategories) {
                // Reapply selected brands
                storedBrands.forEach(brand => {
                    const checkbox = document.querySelector(`input[name="brands"][value="${brand}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });

                // Reapply selected categories
                storedCategories.forEach(category => {
                    const checkbox = document.querySelector(`input[name="categories"][value="${category}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }

            // Function to render facets (brands and categories)
            function renderFacets(data) {
                const brandsList = $('#filters-brands').empty();
                data.brandFacets.forEach((facet, index) => {
                    const listItem = createFacetListItem(facet.key, facet.doc_count, 'brands', index);
                    brandsList.append(listItem);
                });

                const categoriesList = $('#filters-categories').empty();
                data.categoryFacets.forEach((facet, index) => {
                    const listItem = createFacetListItem(facet.key, facet.doc_count, 'categories', index);
                    categoriesList.append(listItem);
                });

                bindFacetCheckboxEvents(); // Bind checkbox events again after rendering
            }

            // Example function to create facet list items (customize as needed)
            function createFacetListItem(key, count, type, index) {
                return `
                    <li class="badge-lst-item filterCat" style="cursor: pointer;">
                        <input type="checkbox" class="btn-check" name="${type}" value="${key}" id="${type}-checkbox-${index}">
                        <label class="fw-500 btn-label" for="${type}-checkbox-${index}">${key} (${count})</label>
                    </li>
                `;
            }

            // Render pagination and add event listeners
            function renderPagination(data) {
                const paginationContainer = document.querySelector('#pageNumbers');
                const totalPages = data.totalPages;
                const currentPage = data.currentPage;

                let paginationHtml = generatePaginationHtml(totalPages, currentPage);
                paginationContainer.innerHTML = paginationHtml;

                // Add event listeners for pagination links
                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        if (!page) return;
                        const pageNumber = parseInt(page);
                        if (!isNaN(pageNumber)) {
                            // Use the existing arrays for selected brands and categories
                            const searchInput = document.querySelector('#search-input');
                            const searchQuery = searchInput ? searchInput.value : ''; 

                            fetchFilteredCoupons(selectedBrands, selectedCategories, searchQuery, pageNumber);
                        }
                    });
                });
            }
        });

        function generatePaginationHtml(totalPages, currentPage) {
            let paginationHtml = '';
            // Add Previous button
            paginationHtml += currentPage === 1 ? 
                `<a class="page-link disabled me-3 ms-3" href="#">&laquo;</a>` :
                `<a class="page-link me-3 ms-3" href="#" data-page="${currentPage - 1}">&laquo;</a>`;

            // Calculate start and end page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust if there are fewer than maxVisiblePages pages
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Display the first page if it's not in the range
            if (startPage > 1) {
                paginationHtml += `<a class="page-link me-2 ms-2" href="#" data-page="1">1</a>`;
                if (startPage > 2) paginationHtml += `<span class="page-link disabled">...</span>`;
            }

            // Add page numbers
            for (let page = startPage; page <= endPage; page++) {
                paginationHtml += page === currentPage ? 
                    `<span class="page-link disabled me-2 ms-2">${page}</span>` :
                    `<a class="page-link me-2 ms-2" href="#" data-page="${page}">${page}</a>`;
            }

            // Display the last page if it is not in the range
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) paginationHtml += `<span class="page-link disabled">...</span>`;
                paginationHtml += `<a class="page-link me-2 ms-2" href="#" data-page="${totalPages}">${totalPages}</a>`;
            }

            // Add Next button
            paginationHtml += currentPage === totalPages ? 
                `<a class="page-link disabled me-3 ms-3" href="#">&raquo;</a>` :
                `<a class="page-link me-3 ms-3" href="#" data-page="${currentPage + 1}">&raquo;</a>`;

            return paginationHtml;
        }
        function showLoader() {
            document.getElementById('loader').style.display = 'block';
        }
        function hideLoader() {
            document.getElementById('loader').style.display = 'none';
        }
        function renderCoupons(couponData) {
            showLoader()
            const couponContainer = document.querySelector('#results');
            const couponContainer2 = document.querySelector('#results2');
            const swiperwrapper = document.querySelector('#swiper-wrapper');
            const resultsHtml = couponData.map((hit, index) => createCouponCard(hit, index + 1)).join('');
            const resultsHtml2 = couponData.map((hit, index) => createCouponCard2(hit, index + 1)).join('');
            const uniqueBrands = new Set();
            const uniqueCouponData = couponData.filter(hit => {
                const brandName = hit["BrandName"];
                if (!uniqueBrands.has(brandName)) {
                    uniqueBrands.add(brandName); 
                    return true;
                }
                return false;
            });
            const resultswiperwrapper = uniqueCouponData.map(hit => swiperwrapperfn(hit)).join('');
            swiperwrapper.innerHTML = resultswiperwrapper;
            couponContainer.innerHTML = resultsHtml;
            couponContainer2.innerHTML = resultsHtml2;
            hideLoader()
        }
        function swiperwrapperfn(hit) {
            var image = hit["brand_image"];
            const brandName =hit["BrandName"];
            const transformedLink = brandName.replace(/\s+/g, '-').toLowerCase();
            return `
                                <div class="swiper-slide swiper-slide-active" style="width:23%">
                                    <div class="card-bg card-5">
                                        <a href="{{ url('store') }}/${transformedLink }" class="">
                                            <div class="icon-float" style="background-image: url('{{ url('upload') }}/${image}');" onload="this.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})'" onerror="this.onerror=null;this.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})';"><img src="{{ url('upload') }}/${image}" style="display:none;" onerror="this.parentNode.style.backgroundImage='url({{ asset('assets/images/default_logo.jpeg') }})'"></div>
                                            <div class="text-wrap">
                                                <span class="d-block text-white">${brandName}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>`;
        }
        function createCouponCard(hit, numericValue) {
            const image = hit["brand_image"];
            const storelink = hit["BrandName"].replace(/\s+/g, '-').toLowerCase();
            const title = hit["coupon_desc"];
            const coupon = hit["coupon_code"];
            const affiliate_link = hit["affiliate_link"];
            const yes_count = hit["yes_count"];

            return `
                <div class="deal-group cat-hide cat-${numericValue}">
                    <div class="deal-card">
                        <div class="box-row flex-wrap hstack justify-content-between">
                            <div class="hstack flex-wrap gap-3 gap-xxl-4">
                                <a href="{{ url('store') }}/${storelink}" class="fix-anchor">
                                    <div class="content-img">
                                        <img class="img-fluid mb-2" src="{{ url('upload') }}/${image}" alt="group-ico" onerror="this.onerror=null;this.src='{{ asset('assets/images/default_logo.jpeg') }}'">
                                    </div>
                                    <p class="text-dark text-center fw-700">${hit["BrandName"]}</p>
                                </a>
                                <div class="content">
                                    <h2 class="off-title">${title}</h2>
                                    <div class="coupon-meta-data">
                                        <div>
                                            <p>Successfully Used: <span class="grey-text-200 Coupon_yes_count-${numericValue}">
                                                ${yes_count > 10 ? `${yes_count} Times` : '10 Times'}
                                            </span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="coupn-btn-wrap">
                                <form action="" method="">
                                    <input type="hidden" name="coupon_id" value="${numericValue}">
                                    <input type="hidden" name="action" id="action" value="no">
                                    <input type="hidden" name="ip" value="123.123.123.123">
                                    <input type="hidden" name="coupon_code" id="coupon_code" value="${coupon}">
                                    <button type="button" class="coupn-btn w-100 coupon_btn_1 cid-${numericValue} view" id="cid-${numericValue}"
                                        data-value="hide-1" data-id="${numericValue}"
                                        data-code="${coupon}" data-affiliate="${affiliate_link}">
                                        <span class="coupn-code">${coupon}</span>
                                        <span class="coupn-title">Get Code</span>
                                    </button>
                                    <div class="view_display coupn-btn coupon_btn_2" style="display:none;text-align:center;">
                                        <span class="is-code-works" style="display: none;">
                                            <span class="d-block pb-2">Did Code ${coupon} work?</span>
                                            <span class="options text-center">
                                                <span class="option-btn-no no badge text-bg-danger No">No</span>
                                                <span class="option-btn-yes no badge text-bg-success Yes">Yes</span>
                                            </span>
                                        </span>
                                        <span class="option-pane-no" style="display: none;">
                                            <span>Thank You</span>
                                            <p>Your Answer Help Us Improve</p>
                                        </span>
                                        <span class="option-pane-yes">
                                            <span class="how-much-form">
                                                <span>How much did you save?</span>
                                                <span class="form-input-wrap">
                                                    <span>&#36;</span>
                                                    <input type="text" class="form-control" name="value" value="0" maxlength="7" oninput="validateValue(this)" required> 
                                                    <a class="coupon-form-btn submit">Submit</a>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <button type="button"
                                        class="coupn-btn w-100 coupon_btn_3 content hide-1 ${numericValue}"
                                        style="display: none;">
                                        <span class="coupn-title coupon-code">${coupon}</span>
                                    </button>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>`;
        }
        function createCouponCard2(hit, numericValue) {
            const image = hit["brand_image"];
            const storelink = hit["BrandName"].replace(/\s+/g, '-').toLowerCase();
            const title = hit["coupon_desc"];
            const coupon = hit["coupon_code"];
            const affiliate_link = hit["affiliate_link"];
            const yes_count = hit["yes_count"];

            return `
            <div class="col-sm-6 mt-4">
                <div class="deal-group cat-hide cat-${numericValue}">
                    <div class="deal-card">
                        <div class="box-row flex-wrap hstack justify-content-between">
                            <div class="hstack flex-wrap gap-3 gap-xxl-4">
                                <a href="{{ url('store') }}/${storelink}" class="fix-anchor">
                                    <div class="content-img">
                                        <img class="img-fluid mb-2" src="{{ url('upload') }}/${image}" alt="group-ico" onerror="this.onerror=null;this.src='{{ asset('assets/images/default_logo.jpeg') }}'">
                                    </div>
                                    <p class="text-dark text-center fw-700">${hit["BrandName"]}</p>
                                </a>
                                <div class="content">
                                    <h2 class="off-title">${title}</h2>
                                    <div class="coupon-meta-data">
                                        <div>
                                            <p>Successfully Used: <span class="grey-text-200 Coupon_yes_count-${numericValue}">
                                                ${yes_count > 10 ? `${yes_count} Times` : '10 Times'}
                                            </span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="coupn-btn-wrap">
                                <form action="" method="">
                                    <input type="hidden" name="coupon_id" value="${numericValue}">
                                    <input type="hidden" name="action" id="action" value="no">
                                    <input type="hidden" name="ip" value="123.123.123.123">
                                    <input type="hidden" name="coupon_code" id="coupon_code" value="${coupon}">
                                    <button type="button" class="coupn-btn w-100 coupon_btn_1 cid-${numericValue} view" id="cid-${numericValue}"
                                        data-value="hide-1" data-id="${numericValue}"
                                        data-code="${coupon}" data-affiliate="${affiliate_link}">
                                        <span class="coupn-code">${coupon}</span>
                                        <span class="coupn-title">Get Code</span>
                                    </button>
                                    <div class="view_display coupn-btn coupon_btn_2" style="display:none;text-align:center;">
                                        <span class="is-code-works" style="display: none;">
                                            <span class="d-block pb-2">Did Code ${coupon} work?</span>
                                            <span class="options text-center">
                                                <span class="option-btn-no no badge text-bg-danger No">No</span>
                                                <span class="option-btn-yes no badge text-bg-success Yes">Yes</span>
                                            </span>
                                        </span>
                                        <span class="option-pane-no" style="display: none;">
                                            <span>Thank You</span>
                                            <p>Your Answer Help Us Improve</p>
                                        </span>
                                        <span class="option-pane-yes">
                                            <span class="how-much-form">
                                                <span>How much did you save?</span>
                                                <span class="form-input-wrap">
                                                    <span>&#36;</span>
                                                    <input type="text" class="form-control" name="value" value="0" maxlength="7" oninput="validateValue(this)" required> 
                                                    <a class="coupon-form-btn submit">Submit</a>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                    <button type="button"
                                        class="coupn-btn w-100 coupon_btn_3 content hide-1 ${numericValue}"
                                        style="display: none;">
                                        <span class="coupn-title coupon-code">${coupon}</span>
                                    </button>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }


        function validateValue(input) {
            // Remove any non-numeric characters except the decimal point
            input.value = input.value.replace(/[^0-9.]/g, '');

            // Ensure only two decimal places
            if (input.value.includes('.')) {
                let parts = input.value.split('.');
                parts[1] = parts[1].slice(0, 2);
                input.value = parts.join('.');
            }

            // Limit the maximum value to 9999.99
            if (parseFloat(input.value) > 9999.99) {
                input.value = '9999.99';
            }
        }

        document.querySelectorAll('.categ_search .search-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.facet-search');
                input.classList.toggle('d-none');
                input.focus();
            });
        });

        document.querySelectorAll('.facet-search').forEach(input => {
            input.addEventListener('input', function() {
                const facetKey = this.dataset.facet;
                const filter = this.value.toLowerCase();
                const items = document.querySelectorAll(`#filters-${facetKey} .badge-lst-item`);

                items.forEach(item => {
                    const label = item.querySelector('label').textContent.toLowerCase();
                    item.style.display = label.includes(filter) ? 'flex' : 'none';
                });
            });
        });

</script>
