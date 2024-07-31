<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login/login.php');
}
$user_id = $_SESSION['user_id'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
  <title>Projects</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Dashboard">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>



  <style type="text/css" media="screen">
    a:link {
      color: white;
      text-decoration: none;
    }

    a:visited {
      color: white;
      text-decoration: none;
    }

    a:hover {
      color: white;
      text-decoration: none;
    }

    a:active {
      color: white;
      text-decoration: none;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .column {
      float: left;
      width: 20%;
      padding: 10px;
    }

    .row {
      margin: 0 -5px;
    }

    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      padding: 10px;
      text-align: center;
      background-color: #f1f1f1;
      text-decoration: none;
      border-radius: 15px;
      color: white;
      transform: scale(0.95);
      border: none;
    }

    h3 {
      font-size: 1.17em;
    }

    .bx {
      font-size: 20pt;
      margin-top: 10px;
    }

    footer {
      margin: 10px;
      width: 100%;
      height: 20px;
      text-align: center;
      color: #57923d;
      font-size: small;
    }

    .topnav {
      overflow: hidden;
    }


    .topnav input[type=text] {
      float: right;
      padding: 8px;
      border: none;
      outline: none;
      margin-top: 8px;
      margin-right: 16px;
      font-size: 15px;
      background-color: #F1F1F1;
      border-radius: 8px;
      color: #898989;
      width: 20%;
      -webkit-transition: width 0.15s ease-in-out;
      transition: width 0.15s ease-in-out;
    }
  </style>
</head>

<body>

  <?php
  include 'head.php';
  ?>



  <div class="container mt-1">
    <h2 style="color:green">Projects</h2>
    <div class="row">
      <div>
        <a id="addNew" class="btn btn-success" href="#">Add New Project</a>
      </div>

      <table class="table table-striped table-bordered rounded">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Project Name</th>
            <th scope="col">Description</th>
            <th scope="col">Difficulty</th>
            <th scope="col">Estimated Time (Hours)</th>
            <th scope="col">Prerequisites</th>
            <th scope="col">Resources</th>
            <th scope="col">Tags</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody id="progressList">
          <!-- Progress data will be appended here -->
        </tbody>

      </table>
    </div>

  </div>

  <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProjectModalLabel">Add New Project</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addProjectForm">
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
              <label for="difficulty">Difficulty</label>
              <select class="form-control" id="difficulty" name="difficulty" required>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
              </select>
            </div>
            <div class="form-group">
              <label for="estimated_time">Estimated Time</label>
              <input type="number" class="form-control" id="estimated_time" name="estimated_time" required>
            </div>
            <div class="form-group">
              <label for="prerequisites">Prerequisites</label>
              <textarea class="form-control" id="prerequisites" name="prerequisites" required></textarea>
            </div>
            <div class="form-group">
              <label for="resources">Resources</label>
              <textarea class="form-control" id="resources" name="resources" required></textarea>
            </div>
            <div class="form-group">
              <label for="tags">Tags</label>
              <select multiple class="form-control" id="tags" name="tags[]"></select>
              <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#addTagModal">Add New Tag</button>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-labelledby="addTagModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTagModalLabel">Add New Tag</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addTagForm">
            <div class="form-group">
              <label for="newTagName">Tag Name</label>
              <input type="text" class="form-control" id="newTagName" name="newTagName" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Tag</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="projectDetailsModal" tabindex="-1" role="dialog" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="projectDetailsModalLabel">Project Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="hiddenProjectId" value="">
          <div id="projectDetailsContent">
            <!-- Project details will be loaded here -->
          </div>
          <button id="joinProjectButton" class="btn btn-primary">Join Project</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#projectDetailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var projectId = button.data('project-id');
        $('#hiddenProjectId').val(projectId);
        fetchProjectDetails(projectId);
      });

      function fetchProjectDetails(projectId) {
        $.ajax({
          url: '../api/projects.php',
          type: 'GET',
          data: {
            operation: 'fetchSingle',
            project_id: projectId
          },
          success: function(response) {
            var project = response;
            var projectDetailsHtml = `
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">${project.title}</h5>
                            <p class="card-text"><strong>Description:</strong> ${project.description}</p>
                            <p class="card-text"><strong>Difficulty:</strong> ${project.difficulty}</p>
                            <p class="card-text"><strong>Estimated Time:</strong> ${project.estimated_time}</p>
                            <p class="card-text"><strong>Prerequisites:</strong> ${project.prerequisites}</p>
                            <p class="card-text"><strong>Resources:</strong> ${project.resources}</p>
                            <p class="card-text"><strong>Tags:</strong> ${project.tag_names}</p>
                        </div>
                    </div>
                `;
            $('#projectDetailsContent').html(projectDetailsHtml);
            $('#joinProjectButton').data('project-id', projectId);
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to fetch project details. Please try again.',
            });
          }
        });
      }

      $('#joinProjectButton').on('click', function() {
        var projectId = $(this).data('project-id');
        var userId = <?php echo $user_id; ?>;
        console.log('Joining project:', projectId, 'for user:', userId);

        $.ajax({
          url: '../api/projects.php',
          type: 'GET',
          data: {
            operation: 'registerUser',
            user_id: userId,
            project_id: projectId
          },
          success: function(response) {
            var jsonResponse = response;
            if (jsonResponse.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'You have successfully joined the project!',
              }).then(() => {
                $('#projectDetailsModal').modal('hide');
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.error,
              });
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to join the project. Please try again.',
            });
          }
        });
      });

      $('#addNew').on('click', function(e) {
        e.preventDefault();
        $('#addProjectModal').modal('show');
        fetchTags();
      });

      $('#addProjectForm').on('submit', function(e) {
        e.preventDefault();
        var formDataArray = $(this).serializeArray();
        var formData = {};
        var tags = [];
        formDataArray.forEach(function(item) {
          if (item.name === 'tags[]') {
            tags.push(item.value);
          } else {
            formData[item.name] = item.value;
          }
        });
        formData['tag_ids'] = JSON.stringify(tags);

        console.log(formData);

        $.ajax({
          url: '../api/projects.php',
          type: 'GET',
          data: $.param(formData) + '&operation=create',
          success: function(response) {
            var jsonResponse = response;
            if (jsonResponse.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: jsonResponse.message,
              }).then(() => {
                $('#addProjectModal').modal('hide');
                refreshProjectList();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: jsonResponse.message,
              });
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to create project. Please try again.',
            });
          }
        });
      });

      function fetchTags() {
        $.ajax({
          url: '../api/tags.php?action=fetch_all',
          type: 'GET',
          success: function(response) {
            var tags = response;
            var tagsSelect = $('#tags');
            tagsSelect.empty();
            tags.forEach(function(tag) {
              tagsSelect.append('<option value="' + tag.tag_id + '">' + tag.tag_name + (tag.synonym ? ', ' + tag.synonym : '') + '</option>');
            });
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error fetching tags: ' + error,
            });
          }
        });
      }

      function refreshProjectList() {
        $.ajax({
          url: '../api/projects.php?operation=fetchAll',
          type: 'GET',
          success: function(response) {
            console.log('Raw response:', response); // Log the raw response

            try {
              var projects = response;
              console.log('Parsed projects:', projects); // Log the parsed projects

              if (Array.isArray(projects)) {
                var projectsHtml = '';
                projects.forEach(function(project) {
                  projectsHtml += '<tr>';
                  projectsHtml += '<td>' + project.title + '</td>';
                  projectsHtml += '<td>' + project.description + '</td>';
                  projectsHtml += '<td>' + project.difficulty + '</td>';
                  projectsHtml += '<td>' + project.estimated_time + '</td>';
                  projectsHtml += '<td>' + project.prerequisites + '</td>';
                  projectsHtml += '<td>' + project.resources + '</td>';
                  projectsHtml += '<td>' + project.tag_names + '</td>';
                  projectsHtml += '<td><button class="view-details-btn btn-success" data-toggle="modal" data-target="#projectDetailsModal" data-project-id="' + project.project_id + '">View Details</button></td>';
                  projectsHtml += '</tr>';
                });
                $('#progressList').html(projectsHtml);
              } else {
                console.error('Expected an array but got:', projects);
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Unexpected response format. Please try again.',
                });
              }
            } catch (e) {
              console.error('Error parsing JSON response:', e);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to parse response. Please try again.',
              });
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error fetching projects: ' + error,
            });
          }
        });
      }

      // Initial load of the project list
      refreshProjectList();

      $('#addTagForm').on('submit', function(e) {
        e.preventDefault();
        var newTagName = $('#newTagName').val();

        $.ajax({
          url: '../api/tags.php',
          type: 'GET',
          data: {
            action: 'add_tag',
            tag_name: newTagName
          },
          success: function(response) {
            var jsonResponse = response;
            if (jsonResponse.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: jsonResponse.message,
              }).then(() => {
                $('#addTagModal').modal('hide');
                fetchTags(); // Refresh the tag list
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: jsonResponse.message,
              });
            }
          },
          error: function(xhr, status, error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to create tag. Please try again.',
            });
          }
        });
      });
    });
  </script>


</body>


</html>