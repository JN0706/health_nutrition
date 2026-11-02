document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('mealForm');

  // Form validation
  form.addEventListener('submit', function (e) {
    const requiredFields = ['age', 'weight', 'height'];
    let valid = true;

    requiredFields.forEach(fieldName => {
      const field = form.elements[fieldName];
      if (!field.value.trim()) {
        field.style.border = '2px solid red';
        valid = false;
      } else {
        field.style.border = '';
      }
    });

    if (!valid) {
      e.preventDefault();
      alert('Please fill in all required fields.');
    }
  });

  // Toggle meal plan details using event delegation
  const wrapper = document.querySelector('.meal-plan-wrapper');
  if (wrapper) {
    wrapper.addEventListener('click', function (e) {
      const header = e.target.closest('.meal-plan-header');
      if (header && wrapper.contains(header)) {
        const details = header.nextElementSibling;
        const chevron = header.querySelector('.chevron');
        if (details) details.classList.toggle('hidden');
        if (chevron) chevron.classList.toggle('rotated');
      }
    });
  }
});
