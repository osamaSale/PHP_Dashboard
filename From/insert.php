<div class="modal" tabindex="-1" role="dialog" id='addUser'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <label>Name</label>
                    <input type='text' name='name' id='name' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Email</label>
                    <input type='text' name='email' id='email' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Password</label>
                    <input type='text' name='password' id='password' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Phone</label>
                    <input type='text' name='phone' id='phone' required class='form-control'>
                </div>
                <div class='form-group'>
                    <label>image</label>
                    <input type='file' name='image' id='image' required class='form-control' />
                </div>
                <div class='form-group'>
                    <label>Admin And User</label>
                    <select class='form-control' name='authorization' id='authorization'>
                        <option selected>Open this select menu</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <input type='submit' value='Submit' class='btn btn-success' id="insert">
            </div>
        </div>
    </div>
</div>