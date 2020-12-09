<div id="menu-wrapper">
    <div id="menuAccordion">
        <div class="option">
            <ul>
                <a href="calendar.php"><li><img src="static/icons/calendar.svg" />Calendar</li></a>
            </ul>
        </div>
        <div class="option-parent collapsed" data-toggle="collapse" data-target="#collapseDentist">
            <h6 class="mx-3"><img src="static/icons/doctor.svg" />Dentist <span class="fa fa-chevron-right"></span></h6>
        </div>
        <div id="collapseDentist" class="option collapse" data-parent="#menuAccordion">
            <ul>
                <a href="add-dentist.php"><li>Add</li></a>
                <a href="dentist-list.php"><li>Dentist List</li></a>
            </ul>
        </div>
        <div class="option-parent collapsed" data-toggle="collapse" data-target="#collapsePatient">
            <h6 class="mx-3"><img src="static/icons/patient.svg" />Patient <span class="fa fa-chevron-right"></span>
            </h6>
        </div>
        <div id="collapsePatient" class="option collapse" data-parent="#menuAccordion">
            <ul>
                <a href="add-patient.php"><li>Add</li></a>
                <a href="patient-list.php"><li>Patient List</li></a>
            </ul>
        </div>
        <div class="option-parent collapsed" data-toggle="collapse" data-target="#collapseAppoinment">
            <h6 class="mx-3"><img src="static/icons/appoinment.svg" />Appoinments <span
                    class="fa fa-chevron-right"></span></h6>
        </div>
        <div id="collapseAppoinment" class="option collapse" data-parent="#menuAccordion">
            <ul>
                <a href="add-appoinment.php"><li>Add</li></a>
                <a href="appoinment-list.php"><li>Appoinment List</li></a>
            </ul>
        </div>
    </div>
</div>
<style>
    #menu-wrapper {
        background: var(--primary-color);
        height: 100%
    }
    .option-parent {
        padding: 15px 0px;
        background: var(--primary-color);
        color: white;
    }

    .option-parent .fa {
        transition: 0.2s;
        float: right;
        transform: rotate(90deg)
    }

    .option-parent.collapsed .fa {
        transform: rotate(0deg)
    }

    .option-parent:hover {
        cursor: pointer;
    }

    .option {
        padding: 15px 0px;
        background: var(--primary-light);
        color: white;
    }

    .option ul {
        padding: 0px;
        margin: 0px;
        list-style-type: none;
        margin-left: 1.5rem;
    }

    .option ul li {
        margin-top: 3px;
        margin-bottom: 3px;
        color: white!important;
    }

    .option ul a {
        text-decoration: none!important;
    }

    .option ul li img,
    .option-parent h6 img {
        height: 20px;
        margin-right: 10px
    }
</style>