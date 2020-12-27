<div id="alertDialog" title="Alert !" style="text-align: center;">
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!-- Add js and css for alert dialog -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- js library for request loading mask-->
<script src="<?= HOME . 'assets/js/jquery.LoadingBox.js' ?>"></script>
<script>
    $(window).on('load', function () {
        getTableListData();
        fillFormWithCachedData('theForm');
        searchAppointments(null, null, 'schedule');
    });
    $(window).on('beforeunload', function () {
        // clear data when leaving add forms and reset edit mode
        const routeIsAdd = window.location.href.indexOf('add') > -1;
        if (routeIsAdd) {
            clearStoredTableRowData();
        }
    });

    function showAlert(msg) {
        $(function () {
            const isAppointmentModalShowing = $("#addAppointmentModal").is(":visible");
            if (isAppointmentModalShowing) {
                alert(msg);
            } else {
                $("#alertDialog").html(msg).dialog();
            }
        });
    }

    /** log client-side error messages
     * errors will be sent so a server for analytics in future
     * remove console.log in production
     */
    function logError(e) {
        // showAlert('Unable to make your request at the moment !');
        console.log(e);
    }

    /** send some data to the controllers */
    function makeAjaxRequest(url, data, callback = null) {
        showLoader();
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (response) {
                hideLoader();
                if (!response) {
                    return;
                }

                if (!callback) {
                    // default callback
                    try {
                        const results = JSON.parse(response);
                        if (!results['result']) {
                            showAlert(results['message']);
                        } else {
                            // redirect to another page determined by controller action
                            window.location = results['message'];
                        }
                    } catch (e) {
                        logError(e);
                    }
                } else {
                    callback(response);
                }
            },
            error: function (xhr, msg) {
                hideLoader();
                showAlert(msg);
            }
        });
    }

    function logout(url) {
        event.preventDefault();
        makeAjaxRequest(url, {signOut: 'signOut'});
    }

    // make loader global
    let loader;

    function showLoader() {
        if (1) {
            const gif = "data:image/gif;base64,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";
            loader = new $.LoadingBox({loadingImageSrc: gif});
        }
    }

    function hideLoader() {
        if (loader) {
            loader.close();
        }
    }

    function setAddNoteButton(data) {
        // activate add note button if it is available
        const addNoteButton = document.getElementById('addNote');
        if (addNoteButton) {
            // this url is set in the controller in the view functions
            // when encoding rowData
            let rowData;
            if (data.rowData) {
                // this function was called from getStoredTableList
                rowData = data.rowData
            } else {
                // this function was called from FillFormWithCachedData
                rowData = data;
            }
            let addNoteUrl;

            if (rowData['addNoteUrl']) {
                addNoteUrl = rowData['addNoteUrl'];
            } else {
                try {
                    addNoteUrl = JSON.parse(rowData)['addNoteUrl'];
                } catch (e) {
                    logError(e);
                }
            }
            if (addNoteUrl) {
                addNoteButton.onclick = () => {
                    window.location = addNoteUrl;
                };
            }
        }
    }

    /** populates tables with html from controllers
     * It works for all tables so the html is determined by the view action of the controllers
     * it is called when the body loads*/
    function getTableListData() {
        const routeIsCalender = window.location.href.indexOf('calendar') > -1;
        const routeIsAdd = window.location.href.indexOf('add') > -1;
        const routeIsEditNote = window.location.href.indexOf('edit-progress-note') > -1;
        // add-note is filtered out too
        if (routeIsAdd || routeIsCalender || routeIsEditNote) {
            return;
        }
        if (!controllerUrl) {
            return;
        }
        let controllerAction = 'view';
        const data = {};
        const routeIsViewNote = window.location.href.indexOf('note') > -1;
        // this is a special case and must to be checked in controller view actions
        if (routeIsViewNote) {
            controllerAction = 'viewNote';
            // this is checked in viewNotes action in the controller
            // this is the patient object that will be used to find Notes
            data['rowData'] = JSON.stringify(getStoredTableRowData());
        }
        setAddNoteButton(data);
        const callback = (response) => {
            try {
                const results = JSON.parse(response);
                const _tableBody = $('tbody[id ="list"]');
                if (!_tableBody) {
                    return;
                }
                const tableBody = _tableBody[0];
                if (!tableBody) {
                    return;
                }
                tableBody.innerHTML = results;
            } catch (e) {
                logError(e);
            }
        };
        data['view'] = controllerAction;
        makeAjaxRequest(controllerUrl, data, callback);
    }

    /** send form data for processing by the controller action */
    function saveForm() {
        event.preventDefault();
        const editingForm = $(event.target);
        // get form field ids specified by formFields attribute attached to forms
        const fields = editingForm.attr('formFields');
        if (!fields) {
            return;
        }
        const formData = {}; // used to hold data that will be submitted to the controller
        const formFields = fields.split(',');
        // loop through the field variables and get their values
        for (let index = 0; index < formFields.length; index++) {
            const fieldId = formFields[index];
            const input = document.getElementById(fieldId);
            if (!input) {
                continue;
            }
            // element is a select option e.g in add dentist
            // get all selected option
            if (input.selectedOptions) {
                const selectedOptions = input.selectedOptions;
                const options = [];
                for (let index = 0; index < selectedOptions.length; index++) {
                    options.push(selectedOptions[index].value);
                }
                formData[fieldId] = JSON.stringify(options);
            } else if (input.type === 'radio') {
                // this means that there are multiple elements with same id and name
                // we have to loop through each and get corresponding value
                let radioValue = 'No';
                const radioButtons = document.getElementsByName(fieldId);
                for (let index = 0; index < radioButtons.length; index++) {
                    const radioButton = radioButtons.item(index);
                    if (radioButton.checked) {
                        radioValue = radioButton.value;
                        break;
                    }
                }
                formData[fieldId] = radioValue;
            } else {
                formData[fieldId] = input.value;
            }
        }
        // this is set when no edit data is found
        let saveMode = editingForm.attr('saveMode');
        if (!saveMode) {
            saveMode = 'add';
        }
        /** add capacity to process add note form */
        const routeIsEditNote = window.location.href.indexOf('edit-progress-note') > -1;
        const routeIsAddNote = window.location.href.indexOf('add-progress-note') > -1;
        let mode = saveMode;
        if (routeIsEditNote) {
            saveMode = 'edit';
            mode = 'editNote';
            formData['Tests'] = extractDiagnosisTests();
        }
        if (routeIsAddNote) {
            saveMode = 'add';
            mode = 'addNote';
            formData['Tests'] = extractDiagnosisTests();
        }
        formData[saveMode] = mode; // set when filling the form

        clearStoredTableRowData();
        makeAjaxRequest(editingForm.attr('action'), formData);
    }

    /** loop through each table row and get input values*/
    function extractDiagnosisTests() {
        // fill in the diagnose test table
        const testTable = document.getElementById('DiagnoseTest');
        // get table rows so that we set column data
        // remember each row has a unique id == progressNote variable
        const rows = testTable.getElementsByTagName('tr');
        // formData is now test
        /* table row ids are these ones below
        * each test will have these row names
        * */
        const rowNames = [
            'ToothNo',
            'Ept',
            'Heat',
            'Percussion',
            'Palpation',
            'ProbeDptLoc',
            'Mobility',
            'SpecialTests'];
        const tests = {};
        for (let nameIndex = 0; nameIndex < rowNames.length; nameIndex++) {
            const rowName = rowNames[nameIndex];
            const row = document.getElementsByClassName(rowName)[0];
            for (let i = 0; i < row.children.length; i++) {
                const rowChild = row.children[i];
                if (!rowChild) {
                    continue;
                }
                const rowGrandChild = rowChild.children;
                if (!rowGrandChild) {
                    continue;
                }
                if (rowGrandChild.length === 0) {
                    continue;
                }
                const input = rowGrandChild[0];
                if (input.id === rowName) {
                    if (!tests[rowName]) {
                        tests[rowName] = [];
                    }
                    if (!tests[rowName][i]) {
                        tests[rowName][i] = [];
                    }
                    tests[rowName][i].push(input.value);
                }
            }
        }
        return tests;
    }

    /** populates html forms with values from a given table row
     * It works for all forms so what gets injected into forms
     * is determined by the controller url and the selected row
     * this is called by the body after loading  */
    function fillFormWithCachedData(formId) {
        event.preventDefault();
        if (!formId) {
            return;
        }
        const editingForm = $('#' + formId);
        if (!editingForm) {
            return;
        }

        editingForm.on('submit', saveForm);
        const formData = getStoredTableRowData();

        if (!formData) {
            // form is being opened in add mode so ignore
            return;
        }
        setAddNoteButton(formData);
        // get form field ids specified by formFields attribute attached to forms
        const fields = editingForm.attr('formFields');
        if (!fields) {
            return;
        }
        // change title of the page
        const routeIsAddNote = window.location.href.indexOf('add-progress-note') > -1;
        if (!routeIsAddNote) {
            $('#title').html('Edit');
        }

        // mark the form for editing
        editingForm.attr('saveMode', 'edit');
        const formFields = fields.split(',');
        setFormFields(formFields, formData);
        /**********handle progress note form
         * this part is executed after filling in the parts
         * of the add-progress-note form that can be filled
         * the table and radio buttons are handled separately
         * */
        const routeIsViewNote = window.location.href.indexOf('note') > -1;
        if (routeIsViewNote) {
            fillProgressNotes(formFields, formData);
        }
    }

    /** fill in records of ProgressNotes array on appointment object*/
    function fillProgressNotes(formFields, formData) {
        if (!formFields || !formData) {
            return;
        }
        // formData is now progressNotes array
        const progressNotes = formData['ProgressNotes'];
        if (!progressNotes) {
            return;
        }

        // fill in the radio buttons
        setFormFields(formFields, progressNotes);
        // fill in the diagnose test table
        const testTable = document.getElementById('DiagnoseTest');
        // get table rows so that we set column data
        // remember each row has a unique id == progressNote variable
        const rows = testTable.getElementsByTagName('tr');
        // this is an array with format [[test1],[test2]]
        const diagnoseTests = progressNotes['Test'];
        /* table row ids are these ones below
            * each test will have these row names
            * */
        const rowNames = [
            'ToothNo',
            'Ept',
            'Heat',
            'Percussion',
            'Palpation',
            'ProbeDptLoc',
            'Mobility',
            'SpecialTests'
        ];
        const getRow = (rowName) => {
            for (let index = 0; index < rows.length; index++) {
                const row = rows.item(index);
                if (row.className === rowName) {
                    return row;
                }
            }
            return null;
        }
        // use this variable to add columns even when there data is not available
        let maximumNoOfColumns = 0;
        for (let nameIndex = 0; nameIndex < rowNames.length; nameIndex++) {
            const rowName = rowNames[nameIndex];
            const row = getRow(rowName);
            if (!row) {
                continue;
            }
            if (!diagnoseTests) {
                continue;
            }
            // formData is now test
            const tests = diagnoseTests[rowName];
            if (!tests) {
                continue;
            }

            // update maximum no of columns to length of test data
            // previous test had for example [0], current test has [0,1]
            // to set maximum no of columns to 2 from 1
            if (maximumNoOfColumns < tests.length) {
                maximumNoOfColumns = tests.length;
            }
            for (let testIndex = 0; testIndex < maximumNoOfColumns; testIndex++) {
                let testValue = tests[testIndex];
                if (!testValue) {
                    testValue = '';
                }
                // add 1 to skip the header row
                const column = row.cells[testIndex + 1];
                let input;
                if (column) {
                    input = column.children[0];
                }

                if (input) {
                    input.name = `${rowName}[${testIndex}]`;
                    input.id = `${rowName}`;
                    input.value = testValue;
                } else {
                    row.insertCell(testIndex + 1).innerHTML = `<input type="text" name="${rowName}[${testIndex}]" id="${rowName}" value="${testValue}" class="form-control testInput">`;
                }
            }
        }
    }

    /** add an extra column to diagnose test table when addColumn button is clicked */
    function addColumnToTestTable() {
        const testTable = document.getElementById('DiagnoseTest');
        // get table rows
        // remember each row has a unique id == progressNote variable
        const rows = testTable.getElementsByTagName('tr');
        for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
            const row = rows[rowIndex];
            // limit number to 3 columns
            if (row.children.length > 3) {
                continue;
            }
            const lastColumn = row.lastElementChild;
            row.insertCell().innerHTML = lastColumn.innerHTML;
        }
    }

    /** remove the last column from diagnose test table when removeColumn button is clicked
     * if only one row is remaining, stop
     * */
    function removeColumnToTestTable() {
        const testTable = document.getElementById('DiagnoseTest');
        // get table rows
        // remember each row has a unique id == progressNote variable
        const rows = testTable.getElementsByTagName('tr');
        for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
            const row = rows[rowIndex];
            if (row.children.length < 3) {
                continue;
            }
            const lastColumn = row.lastElementChild;
            row.removeChild(lastColumn);
        }
    }

    /** given string array of form field attributes, set their values*/
    function setFormFields(formFields, formData) {
        // loop through the field variables and get their values
        for (let index = 0; index < formFields.length; index++) {
            const fieldId = formFields[index];
            let element = document.getElementById(fieldId);

            // also check if attribute is name instead of id
            if (!element) {
                const elements = document.getElementsByName(fieldId);
                if (elements.length > 0) {
                    element = elements.item(0);
                }
            }
            // check if database field is among form fields
            if (element) {
                const value = formData[fieldId];
                if (!value) {
                    continue; // database field not set
                }
                // element is a select option e.g in add dentist
                // get all selected option
                if (element.options) {
                    const options = element.options;
                    for (let index = 0; index < options.length; index++) {
                        const option = options[index];
                        option.selected = optionIsAmongValues(option.value, value);
                    }
                } else if (element.type === 'radio') {
                    // this means that there are multiple elements with same id and name
                    // we have to loop through each and set corresponding value
                    const radioButtons = document.getElementsByName(fieldId);
                    for (let index = 0; index < radioButtons.length; index++) {
                        const radioButton = radioButtons.item(index);
                        // set radio buttons for progress note
                        if (value.toLowerCase() === 'yes' && radioButton.value.toLowerCase() === 'yes') {
                            radioButton.checked = true;
                        }

                        if (value.toLowerCase() === 'no' && radioButton.value.toLowerCase() === 'no') {
                            radioButton.checked = true;
                        }
                    }
                } else {
                    element.setAttribute('value', value);
                    element.value = value;
                }
            }
        }
    }

    function optionIsAmongValues(option, values) {
        return values.find(function (m) {
            return m === option;
        }) !== undefined;
    }

    /** get table row and store its data to local storage
     * this data will be accessed by the editing function when the form is rendered
     * this function is called from the view action of the controllers*/
    function storeTableRowData(jsonDataString, nextUrl) {
        event.preventDefault();
        localStorage.setItem('editData', JSON.stringify(jsonDataString));
        if (!(nextUrl)) {
            return;
        }
        window.location = nextUrl;
    }

    /** get table row data that was stored locally and fill it in the form fields
     * this function is called from the edit form that is being rendered */
    function getStoredTableRowData() {
        event.preventDefault();
        try {
            const rowData = localStorage.getItem('editData');
            if (!rowData) {
                // form is new and the current action is add
                return;
            }
            // un serialize data that was encoded in view action of the controller
            return JSON.parse(rowData);
        } catch (e) {
            logError(e);
        }
    }

    /** remove cached table row data
     * this is called before saving and before unloading the body*/
    function clearStoredTableRowData() {
        localStorage.removeItem('editData');
    }

    /** get table row and and delete it
     * this function is called from the view action of the controllers*/
    function deleteTableRowData(id, actionUrl) {
        event.preventDefault();
        if (!(actionUrl)) {
            return;
        }
        let controllerAction = 'delete';
        const routeIsViewNote = window.location.href.indexOf('note') > -1;
        // this is a special case and must to be checked in controller delete actions
        if (routeIsViewNote) {
            controllerAction = 'deleteNote';
        }
        makeAjaxRequest(actionUrl, {FirebaseId: id, delete: controllerAction});
    }

    /** search table by hiding some rows
     * supports searching upto 3 columns */
    function filterTable() {
        let input, filter, table, tr, td, td2, td3, i;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("searchTable");
        tr = table.getElementsByTagName("tr");
        const columnContainsText = (columnIndex, rowIndex, text = filter) => {
            if (rowIndex === 0) {
                return true; // don't hide header row
            }
            td = tr[rowIndex].getElementsByTagName("td")[columnIndex];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(text) > -1) {
                    return true;
                }
            }
            return false;
        };
        for (i = 0; i < tr.length; i++) {
            const toShow = {};
            // search in first column
            const textInColumn1 = columnContainsText(0, i);
            // search in second column
            const textInColumn2 = columnContainsText(1, i);
            // search in third column
            const textInColumn3 = columnContainsText(2, i);
            const textFound = textInColumn1 || textInColumn2 || textInColumn3;
            if (textFound) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }

    function searchAppointments(dentistName, date, divId) {
        const scheduleDiv = document.getElementById(divId);
        if (!scheduleDiv) {
            return;
        }
        const drawCallback = (response) => {
            if (!response) {
                return;
            }
            try {
                const dentistSelect = document.getElementById('dentistSelect');
                const html = JSON.parse(response);
                if (html) {
                    scheduleDiv.innerHTML = html['appointments'];
                    dentistSelect.innerHTML = html['names'];
                }
            } catch (e) {
                logError(e);
            }
        };
        const data = {view: 'schedule'}
        if (!dentistName) {
            data['DentistName'] = dentistName;
        }
        const searchDate = document.getElementById('searchDate');
        if (searchDate) {
            data['searchDate'] = searchDate.getAttribute('date');
        }
        if (date) {
            data['AppointmentDate'] = date;
            // when date filter is applied, change current displayed date to that date
            searchDate.setAttribute('date', date);
            const newDate = new Date(date);
            searchDate.innerHTML = newDate.toDateString();
        }
        makeAjaxRequest(controllerUrl, data, drawCallback);
    }

    /** hide all div elements without specified dentist name*/
    function filterAppointmentsByDentist(event) {
        const appointmentElements = document.getElementsByClassName('dentist-container');
        if (!appointmentElements) {
            return;
        }
        const dentistName = event.target.value;
        if (!dentistName) {
            return;
        }
        if (dentistName === 'All-Dentists') {
            for (let index = 0; index < appointmentElements.length; index++) {
                // un hide any previously hidden elements
                appointmentElements[index].style.display = 'block';
            }
            return;
        }
        for (let index = 0; index < appointmentElements.length; index++) {
            const element = appointmentElements[index];
            const elementId = element.id;
            if (!elementId) {
                continue;
            }
            if (dentistName !== elementId) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
        }
    }

    /** show schedule when you navigate to add-appointment*/
    function showAppointmentSchedule() {
        event.preventDefault();
        const searchDate = document.getElementById('appointmentDate');
        const dentistName = document.getElementById('dentistSelect');
        searchAppointments(dentistName.value, searchDate.value, 'schedule')
    }

    function showTimeSlot() {
        const element = event.target;
        const DentistName = element.getAttribute('DentistName');
        const FirebaseId = element.getAttribute('FirebaseId');
        const Time = element.getAttribute('Time');
        const Status = element.getAttribute('Status');
        if (Status === 'available') {
            // set fields above on reservation form and
            // make ajax request and create new appointment
            const reserveBtn = $("#ReservationBtn");
            reserveBtn.on('click', function () {
                const PatientNo = document.getElementById('PatientNo').value;
                const data = {view: 'addAppointment', PatientNo: PatientNo, Time: Time, DentistName: DentistName};
                // current appointment date
                const searchDate = document.getElementById('searchDate');
                if (searchDate) {
                    data['appointmentDate'] = searchDate.getAttribute('date');
                }
                makeAjaxRequest(controllerUrl, data);
            });
            $("#DentistNameReserve").html(DentistName);
            const modalId = 'addAppointmentModal';
            $("#" + modalId).modal();
        } else {
            // appointment already exists
            // so make ajax request and get appointment details using firebase id
            // show appointment details
            //
            showAppointmentPopup(FirebaseId);
        }
    }

    function showAppointmentPopup(FirebaseId, url = controllerUrl) {
        if (!FirebaseId) {
            return;
        }
        const showDetailsCallback = (response) => {
            if (!response) {
                return;
            }
            try {
                //get PatientName, AppointmentDate, PatientNo, Time, DentistName
                const results = JSON.parse(response);
                if (results) {
                    $("#Name").html(results['PatientName']);
                    $("#Phone").html(results['PatientNo']);
                    $("#AppointmentDate").html(results['AppointmentDate']);
                    $("#Time").html(results['AppointmentTime']);
                    $("#DentistName").html(results['DentistName']);
                    const modalId = 'appointmentDetailsModal';
                    $("#" + modalId).modal();
                }
            } catch (e) {
                logError(e);
            }
        }
        makeAjaxRequest(url, {view: 'showAppointment', FirebaseId: FirebaseId}, showDetailsCallback);
    }

    function showDentistList() {
        const url = '<?= DENTISTS_CONTROLLER ?>';
        if (!url) {
            return;
        }
        const showDentistCallback = (response) => {
            if (!response) {
                return;
            }
            try {
                if (response) {
                    $("#DentistName").html(response);
                }
            } catch (e) {
                logError(e);
            }
        }
        makeAjaxRequest(url, {viewDentist: 'viewDentist'}, showDentistCallback);
    }
</script>
