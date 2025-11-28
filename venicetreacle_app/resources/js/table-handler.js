/* table_handler.js
 * Nick 2022-07-27
 *
 * Modern vanilla javascript to handle table events: search, sort and pagination
 * using AJAX. Adapted and improved from something attempting the same in buyBackSite
 *
 * Add this script to any page containing a table
 * and markup up the required elements
 *
 */
const tableHandlerInit = () => {

    let abortController = null;

    const searchForm = document.querySelector('#universal-search-form');
    const tableWrapper = document.querySelector('#universal-table-wrapper');
    const paginationWrapper = document.querySelector('#universal-pagination-wrapper');
    const clearFilter = document.querySelector('#clear-filter')

    const checkfilterOn = () => {

        let inputs = searchForm.querySelectorAll("input");
        let selects = searchForm.querySelectorAll("select");

        let filterOn = false;

        inputs.forEach(function(input) {
            if(input.value) {
                filterOn = true;
            }
        });

        selects.forEach(function(select) {
            if(select.value) {
                filterOn = true;
            }
        });

        if(clearFilter) {
            if(filterOn) {
                clearFilter.classList.remove('bg-white');
                clearFilter.classList.add('bg-lime-500');
            }
            else {
                clearFilter.classList.remove('bg-lime-500');
                clearFilter.classList.add('bg-white');
            }
        }
    }

    const displaySpinner = () => {

        if (searchForm.classList.contains('spinner')
            && !document.getElementById('universal-spinner')) {  //only show spinner if it doesnt already exist

            let spinner = document.createElement("div");
            spinner.setAttribute('id', "universal-spinner");
            spinner.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Retrieving data ...</span></div>';
            tableWrapper.prepend(spinner)
        }
    }

    const getAbortSignal = () => {

        // abort any previous running searches and create a new controller
        if(abortController) {
            abortController.abort('AbortedSignal');
        }
        abortController = new AbortController();
        return  abortController.signal;
    }

    const searchAndSort = (searchData) => {

        /* TODO if 404s continue to be a problem, look into handling with retries
         * https://stackoverflow.com/questions/55651169/javascript-fetch-returns-404-occasionally
         * However they might go away being served by a proper server
         */

        /* Nick: date picker stuff - remove if not needed
        if (event.oldDate === null) {
            console.log('returning false - do not want ajax on page load');
            return false;
        }*/

        displaySpinner();
        const signal = getAbortSignal();

        fetch(CURRENT_URL + "?" + searchData, {
            signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).then(response => {
            abortController = null;
            if (!response.ok) {
                throw new Error(`HTTP error, status = ${response.status}`);
            }
            return response.json();
        }).then(data => {
            tableWrapper.innerHTML = data.itemsHtml;
            paginationWrapper.innerHTML = data.paginationHtml;
            rebindEvents();
            checkfilterOn();
        }).catch(err => {
            if(err !== 'AbortedSignal') {
                console.warn('Something went wrong. ', err);
            }
            
        });

    }

    const search = (event) => {

        const formData = new FormData(searchForm);
        const searchData = new URLSearchParams(formData).toString();

        searchAndSort(searchData);
    }

    const sort = (event) => {

        const sortColumn = event.target;
        const sortField = sortColumn.getAttribute('data-sort');

        let sortDirection = "asc";
        if(sortColumn.classList.contains('asc')) {
            sortDirection = "desc";
        }

        document.getElementById("sort_field").value = sortField;
        document.getElementById("sort_direction").value = sortDirection;

        const formData = new FormData(searchForm);
        const searchData = new URLSearchParams(formData).toString();

        searchAndSort(searchData);
    }

    const changePage = (event) => {

        const pageLink = event.target.closest("a"); // without the closest, it uses the svg :(
        const searchData = pageLink.getAttribute('data-link'); //get the querystring built by the paginator

        searchAndSort(searchData);
        
        event.preventDefault(); //stop the link from working
    }

    const rebindEvents = () => {

        // for those elements that are redrawn with AJAX
        const sortRow = document.querySelector('#universal-sort-row');
        if(sortRow) {
            const sortColumns = sortRow.getElementsByClassName("sort");
            for (var i = 0; i < sortColumns.length; i++) {
                sortColumns[i].addEventListener('click', sort);
            }
        }

        if(paginationWrapper) {           
            const pages = paginationWrapper.querySelectorAll("[data-link]");
            pages.forEach(function(page) {
                page.addEventListener('click', changePage);
            });
        }
    }

    const bindEvents = () => {

        let inputs = searchForm.querySelectorAll("input");
        let selects = searchForm.querySelectorAll("select");
        let datePickers = searchForm.querySelectorAll("input.datetimepicker");

        if(searchForm) {
            
            inputs.forEach(function(input) {
                input.addEventListener('keyup', search);
            });

            selects.forEach(function(select) {
                select.addEventListener('change', search);
            });

            datePickers.forEach(function(datePicker) {
                const fp = flatpickr(datePicker, {
                    dateFormat:"Y-m-d",
                    disableMobile:true,
                    onClose: function(selectedDates, dateStr, instance) {
                        search();
                    }
                });
            });
        }

        if(clearFilter) {
            clearFilter.addEventListener('click', (event) => {

                inputs.forEach(function(input) {
                    input.value = '';
                });

                selects.forEach(function(select) {
                    select.selectedIndex = '';
                });

                searchAndSort('search=');

                event.preventDefault(); //stop the link from working
            });
        }

        rebindEvents();
        checkfilterOn();
    }

    bindEvents();

};


window.addEventListener("load", tableHandlerInit);
