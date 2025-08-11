<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kitchen Meal POS</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Icons (Bootstrap Icons) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  body, html {
    height: 100%;
    background: #f0f2f5;
  }
  .container {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .meal-grid {
    display: grid;
    grid-template-columns: repeat(2, 200px);
    grid-gap: 1.5rem;
    margin-bottom: 2rem;
  }
  .meal-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    padding: 2rem;
    text-align: center;
    transition: transform 0.2s ease;
  }
  .meal-card:hover {
    transform: scale(1.05);
  }
  .meal-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
  }
  .meal-breakfast {
    color: #ff6f61;
  }
  .meal-tea {
    color: #6f42c1;
  }
  .meal-lunch {
    color: #20c997;
  }
  .meal-supper {
    color: #fd7e14;
  }
  #mealModal .modal-content {
    border-radius: 15px;
  }
  .select2-container--bootstrap5 .select2-selection {
    height: 44px;
    padding: 6px 12px;
  }
</style>
</head>
<body>

<div class="container">
  <h2 class="mb-4">Kitchen Meal POS</h2>

  <div class="meal-grid" role="list">
    <div class="meal-card meal-breakfast" tabindex="0" role="listitem" data-meal="breakfast" aria-label="Breakfast meal">
      <i class="bi bi-cup-fill meal-icon"></i>
      <h4>Breakfast</h4>
    </div>
    <div class="meal-card meal-tea" tabindex="0" role="listitem" data-meal="tea" aria-label="Tea Break meal">
      <i class="bi bi-cup-hot-fill meal-icon"></i>
      <h4>Tea Break</h4>
    </div>
    <div class="meal-card meal-lunch" tabindex="0" role="listitem" data-meal="lunch" aria-label="Lunch meal">
      <i class="bi bi-egg-fried meal-icon"></i>
      <h4>Lunch</h4>
    </div>
    <div class="meal-card meal-supper" tabindex="0" role="listitem" data-meal="supper" aria-label="Supper meal">
      <i class="bi bi-basket-fill meal-icon"></i>
      <h4>Supper</h4>
    </div>
  </div>

  <!-- Alert message -->
  <div id="alertBox" class="alert d-none text-center" role="alert" aria-live="assertive"></div>

  <!-- Modal -->
  <div class="modal fade" id="mealModal" tabindex="-1" aria-labelledby="mealModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="mealForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mealModalLabel">Select Person</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="meal_type" id="mealType" />
          <label for="personSelect" class="form-label">Person picking meal:</label>
          <select id="personSelect" name="person_id" class="form-select" style="width: 100%;" required></select>
        </div>
        <div class="modal-footer">
          <button type="submit" id="submitBtn" class="btn btn-primary w-100">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS (Popper + Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 Bootstrap 5 theme -->
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<script>
$(function(){
  const modalEl = document.getElementById('mealModal');
  const modal = new bootstrap.Modal(modalEl);
  let currentMeal = '';

  // Show alert with auto-hide and slide up effect
  function showAlert(type, message) {
    const alertBox = $('#alertBox');
    const colors = {
      'breakfast': 'alert-danger',
      'tea': 'alert-primary',
      'lunch': 'alert-success',
      'supper': 'alert-warning'
    };
    alertBox
      .removeClass('d-none alert-danger alert-primary alert-success alert-warning')
      .addClass(colors[type] || 'alert-info')
      .html(message)
      .fadeIn()
      .delay(1500)
      .fadeOut();
  }

  

  // Initialize Select2 on the dropdown with ajax search
  function initSelect2(meal) {
    $('#personSelect').empty().select2({
        theme: 'bootstrap4',
        placeholder: 'Search or select person',
        dropdownParent: $('#mealModal'),
        ajax: {
        url: 'get_people.php',
        dataType: 'json',
        delay: 300,
        data: params => ({
            meal_type: meal,
            term: params.term || '',
            page: params.page || 1  // send current page to backend
        }),
        processResults: (data, params) => {
            if (data.error) {
            return { results: [], pagination: { more: false } };
            }
            params.page = params.page || 1;
            return {
            results: data.items,
            pagination: {
                more: data.more // server returns whether more pages exist
            }
            };
        },
        cache: true
        },
        minimumInputLength: 0,
        allowClear: true,
        width: '100%',
    });
    }
  

  // When a meal card is clicked
  $('.meal-card').click(function(){
    currentMeal = $(this).data('meal');
    $('#mealType').val(currentMeal);
    $('#mealModalLabel').text(`Select person for ${currentMeal.charAt(0).toUpperCase() + currentMeal.slice(1)}`);
    $('#submitBtn').text(`Confirm ${currentMeal.charAt(0).toUpperCase() + currentMeal.slice(1)}`);
    initSelect2(currentMeal);
    modal.show();
  });

  // Handle form submission
  $('#mealForm').submit(function(e){
    e.preventDefault();

    $.post('save_meal.php', $(this).serialize())
      .done(function(response){
        let res = (typeof response === 'string') ? JSON.parse(response) : response;
        if(res.status === 'success'){
          showAlert(currentMeal, res.message);
          modal.hide();
          $('#personSelect').val(null).trigger('change');
        } else {
          showAlert(currentMeal, res.message || 'Error saving meal.');
        }
      })
      .fail(function(){
        showAlert(currentMeal, 'Server request failed.');
      });
  });

});
</script>

</body>
</html>
