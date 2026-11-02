document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("RegistrationForm");
  const errorBox = document.getElementById("form-errors");
  const usernameField = form.username;
  const emailField = form.email;
  const phoneField = form.phone;
  const dobField = form.DoB;
  const heightField = form.height;
  const weightField = form.weight;
  const passwordField = form.password;
  const confirmField = form.confirm_password;

  function showError(message) {
    errorBox.textContent = message;
    errorBox.style.display = "block";
  }

  function clearError() {
    errorBox.textContent = "";
    errorBox.style.display = "none";
  }

  function isValidEmail(email) {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
  }

  function isValidPhone(phone) {
    const regex = /^(\+?60|0)1[0-46-9][0-9]{7,8}$/;
    return regex.test(phone);
  }

  form.addEventListener("submit", (e) => {
    clearError();

    const username = usernameField.value.trim();
    const email = emailField.value.trim().toLowerCase();
    const phone = phoneField.value.trim();
    const dob = dobField.value;
    const height = parseInt(heightField.value);
    const weight = parseFloat(weightField.value);
    const pwd = passwordField.value;
    const confirm = confirmField.value;

    if (!/^[a-zA-Z0-9_]{4,20}$/.test(username)) {
      e.preventDefault();
      showError("Username must be 4–20 characters and contain only letters, numbers, or underscores.");
      return;
    }

    if (!isValidEmail(email)) {
      e.preventDefault();
      showError("Please enter a valid email address.");
      return;
    }

    if (!isValidPhone(phone)) {
      e.preventDefault();
      showError("Please enter a valid Malaysian phone number.");
      return;
    }

    if (!dob) {
      e.preventDefault();
      showError("Please select your date of birth.");
      return;
    }

    if (isNaN(height) || height < 50 || height > 300) {
      e.preventDefault();
      showError("Enter a valid height between 50–300 cm.");
      return;
    }

    if (isNaN(weight) || weight < 10 || weight > 500) {
      e.preventDefault();
      showError("Enter a valid weight between 10–500 kg.");
      return;
    }

    if (pwd.length < 8 || pwd !== confirm) {
      e.preventDefault();
      showError("Passwords must match and be at least 8 characters.");
      return;
    }

    // Normalize email before submission
    emailField.value = email;
  });

  usernameField.addEventListener("input", () => {
    const username = usernameField.value.trim();
    if (username.length === 0) {
      clearError();
      return;
    }

    fetch(`registration.php?username=${encodeURIComponent(username)}`)
      .then(response => response.json())
      .then(data => {
        if (!data.available) {
          showError("Username is already taken. Please choose another.");
        } else {
          clearError();
        }
      })
      .catch(() => {
        showError("Error checking username availability.");
      });
  });
});
