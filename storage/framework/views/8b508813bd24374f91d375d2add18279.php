<div class="position-fixed top-0 end-0 p-3" style="z-index:1051">
  <div id="liveToast" class="toast modern-toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body d-flex align-items-center p-3">
      <i id="toast-icon" class="me-3" style="font-size: 1.2rem;"></i>
      <div id="toastmessage_body" class="flex-grow-1 fw-medium"></div>
      <button type="button" class="btn-close ms-3" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  <script>
    function showtoastmessage(response) {
      var toastEl = document.getElementById('liveToast');
      if (toastEl) {
        var toastbody = document.getElementById('toastmessage_body');
        var toasticon = document.getElementById('toast-icon');
        
        // Reset classes
        toasticon.className = 'me-3';
        
        // Set icon and color based on type
        if (response && response.type) {
          if (response.type == "success") {
            toasticon.className += ' bi bi-check-circle-fill text-success';
          }
          else if (response.type == "warning") {
            toasticon.className += ' bi bi-exclamation-triangle-fill text-warning';
          }
          else if (response.type == "info") {
            toasticon.className += ' bi bi-info-circle-fill text-info';
          }
          else {
            toasticon.className += ' bi bi-x-circle-fill text-danger';
          }
        }
        else {
          toasticon.className += ' bi bi-x-circle-fill text-danger';
        }
        
        toastbody.innerText = response && response.message ? response.message : "No Message";
        var toast = new bootstrap.Toast(toastEl, {
          animation: true,
          autohide: true,
          delay: 3000
        });
        toast.show();
      }
    }

  </script>
  
  <style>
    .modern-toast {
      background: white;
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
      backdrop-filter: blur(10px);
      min-width: 350px;
    }
    
    .modern-toast .toast-body {
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.95);
    }
  </style>
</div><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/partials/toast.blade.php ENDPATH**/ ?>