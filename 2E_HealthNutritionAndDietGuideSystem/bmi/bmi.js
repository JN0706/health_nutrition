function calculateBMI() {
  const weight = parseFloat(document.getElementById('weight').value);
  const height = parseFloat(document.getElementById('height').value);
  const result = document.getElementById('result');

  if (!weight || !height || weight <= 0 || height <= 0) {
    result.textContent = "Please enter valid values.";
    return;
  }

  const heightInMeters = height / 100;
  const bmi = weight / (heightInMeters * heightInMeters);
  const roundedBMI = bmi.toFixed(1);

  let category = "";
  if (bmi < 18.5) category = "Underweight";
  else if (bmi < 24.9) category = "Normal weight";
  else if (bmi < 29.9) category = "Overweight";
  else category = "Obese";

  result.innerHTML = `
    <strong>Your BMI:</strong> ${roundedBMI}<br>
    <strong>Category:</strong> ${category}
  `;

  if (loggedIn) {
    document.getElementById('bmiValue').value = roundedBMI;
    document.getElementById('bmiCategory').value = category;
    document.getElementById('saveForm').style.display = 'block';
  } else {
    document.getElementById('tempBmiValue').value = roundedBMI;
    document.getElementById('tempBmiCategory').value = category;
    document.getElementById('savePrompt').style.display = 'block';
  }
}
