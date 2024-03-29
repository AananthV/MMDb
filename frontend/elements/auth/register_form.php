<!-- Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-success">
        <h5 class="modal-title" id="ModalCenterTitle">Register</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="register-message" class="d-none"></p>
        <form oninput="checkRegister()">
          <div class="form-group">
            <label for="registerUsername">Username</label>
            <input type="text" class="form-control border" id="registerUsername" aria-describedby="usernameHelp" placeholder="Username" autocomplete="username" required>
            <small id="usernameHelp" class="form-text text-muted">Username must be atleast 6 characters long and must contain only lowercase letters and numbers.</small>
          </div>
          <div class="form-group">
            <label for="registerFirstName">Full Name</label>
            <input type="text" class="form-control border" id="registerFullName" placeholder="Full Name" autocomplete="name" required>
          </div>
          <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" class="form-control border" id="registerPassword" aria-describedby="passwordHelp" placeholder="Password" autocomplete="new-password" required>
            <small id="passwordHelp" class="form-text text-muted">Password be atleast 8 characters long and must contain a lowercase alphabet, an uppercase alphabet, a number and a speacial character.</small>
          </div>
          <div class="d-flex flex-row justify-content-between align-items-center">
            <button type="button" class="btn btn-success" onclick="register()">Register</button>
            <button type="button" class="btn btn-link" onclick="open_login_modal()">Have an account?</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function show_register_message() {
    document.querySelector('#register-message').classList.remove('d-none');
  }
  function checkRegister() {
    let elements = {
      'username': document.querySelector('#registerUsername'),
      'name': document.querySelector('#registerFullName'),
      'password': document.querySelector('#registerPassword')
    }
    let register_data = {
      'username': document.querySelector('#registerUsername').value,
      'name': document.querySelector('#registerFullName').value,
      'password': document.querySelector('#registerPassword').value
    }
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText == 'ERROR') {
          document.querySelector('#demo').innerHTML = 'Something went wrong... try again!';
        } else {
          let validation = JSON.parse(this.responseText);
          for(let [key, element] of Object.entries(elements)) {
            if(validation[key] == true) {
              element.classList.remove('border-danger');
            } else if (element.value.length > 0) {
              element.classList.add('border-danger');
            }
          }
        }
      }
    };
    xhttp.open("POST", "<?php echo BACKEND . 'check_register.php';?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('register=' + JSON.stringify(register_data));
  }
  function register() {
    let register_data = {
      'username': document.querySelector('#registerUsername').value,
      'name': document.querySelector('#registerFullName').value,
      'password': document.querySelector('#registerPassword').value
    }
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        switch(this.responseText) {
          case 'SUCCESS':
            document.querySelector('#register-message').innerHTML = 'Register Successful';
            toggle_auth_buttons();
            closeAllModals();
            document.querySelector('#login-message').innerHTML = 'Registration Successful';
            show_login_message();
            document.querySelector('#login-button').click();
            break;
          case 'ERROR: LOGGED IN':
            document.querySelector('#register-message').innerHTML = 'Already Logged In!';
            show_register_message();
            break;
          default:
            document.querySelector('#register-message').innerHTML = 'Enter valid values.';
            show_register_message();
            break;
        }
      }
    };
    xhttp.open("POST", "<?php echo BACKEND . 'register.php';?>", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('register=' + JSON.stringify(register_data));
  }
</script>
