<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button class="modal-close-button" data-dismiss="modal"><i class="fa fa-times-circle-o"></i></button>
                <h6>Appointment Details</h6>
                <hr>
                <table class="table">
                    <tr>
                        <td>Name</td>
                        <td id="Name"></td>
                    </tr>
                    <tr>
                        <td>Appointment</td>
                        <td id="AppointmentDate"></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td id="Phone"></td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td id="Time"></td>
                    </tr>
                    <tr>
                        <td>Time booked for</td>
                        <td id="DentistName"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-body {
        padding: 10px;
        background: rgb(219, 226, 237);
    }

    .modal-close-button {
        border: 0px;
        background: transparent;
        position: absolute;
        right: 0;
        top: 0;
        padding: 0px 10px 10px 10px;
        font-size: 22px;
        color: #d43939;
    }
</style>
