<div class="modal" tabindex="-1" role="dialog" id='editUser'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                <input type='text' name='id' id='id' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Name</label>
                    <input type='text' name='name1' id='name1' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Email</label>
                    <input type='text' name='email1' id='email1' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Password</label>
                    <input type='password' name='password' id='password1' required class='form-control' disabled>
                </div>
                <div class='form-group'>
                    <label>Phone</label>
                    <input type='text' name='phone' id='phone1' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>image</label>
                    <input type='file' name='image' id='image1' required class='form-control' />
                </div>
                <div class='form-group'>
                    <label>Admin And User</label>
                    <select class='form-control' name='authorization1' id='authorization1'>
                        <option selected>Open this select menu</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <input type='submit' value='Submit' class='btn btn-success' id="update">
            </div>
        </div>
    </div>
</div>