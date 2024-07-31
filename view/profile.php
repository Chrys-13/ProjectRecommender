<?php
require '../settings/core.php';
echo '<script>const userId = ' . getSession('user_id') . ';</script>';
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login/login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <title>User Profile</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .card {
      background-color: honeydew;
    }

    .table th,
    .table td {
      border-color: honeydew;
    }
  </style>
</head>

<body>
  <?php require 'head.php';
  ?>
  <div class="container mt-5">
    <h1 style="color:green">User Profile</h1>
    <div></div>
    <div class="card" style="background-color:honeydew">
      <div class="card-body">
        <table class="table">
          <tbody>
            <tr>
              <th scope="row"><i class="fas fa-user"></i> Username:</th>
              <td id="username"></td>
            </tr>
            <tr>
              <th scope="row"><i class="fas fa-envelope"></i> Email:</th>
              <td id="email"></td>
            </tr>
            <tr>
              <th scope="row"><i class="fas fa-heart"></i> Interests:</th>
              <td id="interests" class="text-capitalize"></td>
            </tr>
            <tr>
              <th scope="row"><i class="fas fa-code"></i> Skills:</th>
              <td id="skills" class="text-capitalize"></td>
            </tr>
            <tr>
              <th scope="row"><i class="fas fa-bullseye"></i> Goals:</th>
              <td id="goals" class="text-capitalize"></td>
            </tr>
            <tr>
              <th scope="row"><i class="fas fa-chart-line"></i> Current Skill Level:</th>
              <td id="current_skill_level" class="text-capitalize"></td>
            </tr>
          </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal"><i class="fas fa-edit"></i> Edit Profile</button>
      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editProfileForm">
            <div class="form-group">
              <label for="editInterests">Interests</label>
              <textarea class="form-control" id="editInterests" required></textarea>
            </div>
            <div class="form-group">
              <label for="editSkills">Skills</label>
              <textarea class="form-control" id="editSkills"></textarea>
            </div>
            <div class="form-group">
              <label for="editGoals">Goals</label>
              <textarea class="form-control" id="editGoals"></textarea>
            </div>
            <div class="form-group">
              <label for="editCurrentSkillLevel">Current Skill Level</label>
              <select class="form-control" id="editCurrentSkillLevel">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
      }


      // Fetch and display user data
      function fetchUserData() {
        $.ajax({
          url: '../api/profile.php',
          type: 'GET',
          data: {
            method: 'getProfile',
            user_id: userId
          },
          dataType: 'json',
          success: function(response) {
            $('#username').text(response.username);
            $('#email').text(response.email);
            $('#interests').text(toTitleCase(JSON.parse(response.interests).join(', ')));
            $('#skills').text(toTitleCase(response.skills));
            $('#goals').text(toTitleCase(response.goals));
            $('#current_skill_level').text(toTitleCase(response.current_skill_level));
          },
          error: function() {
            Swal.fire('Error', 'An error occurred while fetching your profile.', 'error');
          }
        });
      }

      fetchUserData();

      $('#editProfileModal').on('show.bs.modal', function() {
        $('#editInterests').val($('#interests').text());
        $('#editSkills').val($('#skills').text());
        $('#editGoals').val($('#goals').text());
        $('#editCurrentSkillLevel').val($('#current_skill_level').text());
      });

      // Handle form submission for editing profile
      $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();

        const updatedData = {
          method: 'updateProfile',
          user_id: userId,
          interests: $('#editInterests').val(),
          skills: $('#editSkills').val(),
          goals: $('#editGoals').val(),
          current_skill_level: $('#editCurrentSkillLevel').val()
        };

        $.ajax({
          url: '../api/profile.php',
          type: 'POST',
          data: updatedData,
          dataType: 'json',
          success: function(response) {
            if (response.message === "Profile updated successfully") {
              Swal.fire('Success', response.message, 'success');
              $('#editProfileModal').modal('hide');
              fetchUserData(); // Refresh user data
            } else {
              Swal.fire('Error', response.message, 'error');
            }
          },
          error: function() {
            Swal.fire('Error', 'An error occurred while updating your profile.', 'error');
          }
        });
      });
    });
  </script>
</body>

</html>