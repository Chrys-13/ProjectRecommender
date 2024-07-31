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
  <title>Recommendations</title>
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

    .selected {
      background-color: green;
      color: white;
    }
  </style>
</head>

<body>

  <?php
  include 'head.php';
  ?>



  <div class="container mt-1">
    <h2 style="color:green">Recommendations</h2>
    <ul class="nav nav-tabs" id="recommendationTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="projects-tab" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">Projects</a>
      </li>
      <li class="nav-item">
        <a style="color:green" class="nav-link" id="additional-info-tab" data-toggle="tab" href="#additional-info" role="tab" aria-controls="additional-info" aria-selected="false">Recommended Projects</a>
      </li>
    </ul>
    <div class="tab-content" id="recommendationTabsContent">
      <!-- Projects Tab -->
      <div class="tab-pane fade show active" id="projects" role="tabpanel" aria-labelledby="projects-tab">
        <div class="row">
          <div class="col-md-12">
            <h3 id="instruction">Select what projects interest you</h3>
            <table class="table table-striped table-bordered rounded">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Project Name</th>
                  <th scope="col">Description</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody id="progressList">
                <!-- Progress data will be appended here -->
              </tbody>
            </table>
            <div class="d-flex justify-content-between mt-3" id="buttonsFrame">
              <button class="btn bg-warning flex-fill mr-2" id="reload" onclick="regenerateProjects()">
                <i class="fas fa-pause"></i> Regenerate Projects
              </button>
              <button class="btn bg-success flex-fill ml-2" id="proceed">
                <i class="fas fa-forward"></i> Proceed
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Additional Info Tab -->
      <div class="tab-pane fade" id="additional-info" role="tabpanel" aria-labelledby="additional-info-tab">
        <table class="table table-striped table-bordered rounded">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Project Name</th>
              <th scope="col">Description</th>
              <th scope="col">Difficulty</th>
              <th scope="col">Estimated Time (Hours)</th>
              <th scope="col">Prerequisites</th>
              <th scope="col">Resources</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody id="recommendedProjects">
            <!-- Progress data will be appended here -->
          </tbody>
        </table>
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
        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {
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
      });

      function refreshProjectList() {
        $.ajax({
          url: '../api/recommendations.php?operation=fetchRandom',
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
                  projectsHtml += '<td><button class="select-button-first" data-project-id="' + project.tag_ids + '">Select</button></td>';
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

      // Initial fetch
      refreshProjectList();


      function recommendedProjectList() {
        $.ajax({
          url: '../api/recommendations.php?operation=fetchRecommendations&user_id=<?php echo $user_id ?>',
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
                  projectsHtml += '<td><button class="view-details-btn btn-success" data-toggle="modal" data-target="#projectDetailsModal" data-project-id="' + project.project_id + '">View Details</button></td>';
                  projectsHtml += '</tr>';
                });
                $('#recommendedProjects').html(projectsHtml);
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

      // Initial fetch
      recommendedProjectList();


      // Use event delegation to handle clicks on dynamically added buttons
      $(document).on('click', '.select-button-first', function() {
        const tagIds = $(this).data('project-id');
        selectedTagIds.push(tagIds);
        $(this).addClass('selected');
        $(this).text('Selected');
        console.log('Selected tag IDs:', selectedTagIds); // Debugging log
      });

      let selectedTagIds = [];

      window.regenerateProjects = function() {
        refreshProjectList();
      };
      $(document).on('click', '#proceed', function() {
        var user_id = <?php echo $_SESSION['user_id'] ?>;
        if (selectedTagIds.length === 0) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select at least one project to proceed.',
          });
          return;
        }

        newTagList = selectedTagIds.join(',');
        newTagList = [...new Set(newTagList.split(','))].join(',');
        console.log('Final tag list:', newTagList); // Debugging log

        $.ajax({
          url: '../api/recommendations.php?operation=recommend&tagIds=' + newTagList + '&user_id=' + user_id,
          type: 'GET',
          success: function(response) {
            console.log('Raw response:', response); // Log the raw response
            try {
              var projects = response;
              console.log('Parsed projects:', projects); // Log the parsed projects
              if (Array.isArray(projects)) {
                $('#instruction').text('Join the projects that interest you');
                var projectsHtml = '';
                projects.forEach(function(project) {
                  projectsHtml += '<tr>';
                  projectsHtml += '<td>' + project.title + '</td>';
                  projectsHtml += '<td>' + project.description + '</td>';
                  projectsHtml += '<td><button class="select-button" data-project-id="' + project.project_id + '">Join</button></td>';
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

        $(document).on('click', '.select-button', function() {
          var project_id = $(this).data('project-id');
          var user_id = <?php echo $_SESSION['user_id'] ?>;

          $.ajax({
            url: '../api/projects.php?operation=registerUser&user_id=' + user_id + '&project_id=' + project_id,
            type: 'GET',
            success: function(response) {
              console.log('User registered to project:', response);
              $.ajax({
                url: '../api/recommendations.php?operation=createRecommendation&user_id=' + user_id + '&project_id=' + project_id,
                type: 'GET',
                success: function(response) {
                  console.log('Recommendation created:', response);
                  Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'You have successfully joined the project and a recommendation has been created.',
                  });
                  refreshProjectList();
                },
                error: function(xhr, status, error) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error creating recommendation: ' + error,
                  });
                }
              });
            },
            error: function(xhr, status, error) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error registering user to project: ' + error,
              });
            }
          });


        });
      });
    });
  </script>


</body>


</html>