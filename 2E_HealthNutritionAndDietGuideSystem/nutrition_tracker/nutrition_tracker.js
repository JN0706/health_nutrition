document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('meal-form');
  const logList = document.getElementById('log-list');
  const username = window.loggedInUsername;

  let selectedDate = new Date().toLocaleDateString('en-CA');
  let meals = [];
  let history = [];

  async function fetchMeals() {
    console.log("📦 Fetching meals for:", selectedDate, "as user:", username);
    try {
      const res = await fetch(`../nutrition_tracker/getMeals.php?username=${encodeURIComponent(username)}&date=${selectedDate}`);
      meals = await res.json();
      renderMeals();
      updateSummary();
    } catch (err) {
      console.error('Error fetching meals:', err);
    }
  }

  async function fetchHistory() {
    try {
      const res = await fetch(`../nutrition_tracker/getHistory.php?username=${encodeURIComponent(username)}`);
      history = await res.json();
      renderProgressChart();
    } catch (err) {
      console.error('Error fetching history:', err);
    }
  }

  function updateSummary() {
    const totals = { calories: 0, protein: 0, carbs: 0, fat: 0, vitaminC: 0 };

    meals.forEach(meal => {
      totals.calories += parseInt(meal.calories);
      totals.protein += parseInt(meal.protein);
      totals.carbs += parseInt(meal.carbs);
      totals.fat += parseInt(meal.fat);
      totals.vitaminC += parseInt(meal.vitaminC || 0);
    });

    document.getElementById('total-calories').textContent = totals.calories;
    document.getElementById('total-protein').textContent = totals.protein;
    document.getElementById('total-carbs').textContent = totals.carbs;
    document.getElementById('total-fat').textContent = totals.fat;
    document.getElementById('total-vitaminC').textContent = totals.vitaminC;

    const goals = { calories: 2000, protein: 100, carbs: 250, fat: 70, vitaminC: 90 };
    for (let key in goals) {
      const percent = Math.min((totals[key] / goals[key]) * 100, 100);
      document.getElementById(`bar-${key}`).value = totals[key];
      document.getElementById(`percent-${key}`).textContent = `${percent.toFixed(1)}%`;
    }
  }

  function renderMeals() {
    logList.innerHTML = '';
    if (meals.length === 0) {
      const emptyMsg = document.createElement('li');
      emptyMsg.textContent = "No meals logged for today.";
      logList.appendChild(emptyMsg);
      return;
    }

    const grouped = {};

    meals.forEach(meal => {
      const date = new Date(meal.created_at).toLocaleDateString();
      if (!grouped[date]) grouped[date] = [];
      grouped[date].push(meal);
    });

    for (const date in grouped) {
      const dateHeader = document.createElement('li');
      dateHeader.innerHTML = `<strong>${date}</strong>`;
      logList.appendChild(dateHeader);

      grouped[date].forEach(meal => {
        const time = new Date(meal.created_at.replace(' ', 'T')).toLocaleTimeString('en-MY', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        });

        const li = document.createElement('li');
        li.classList.add('meal-entry');
        li.innerHTML = `
          <div class="meal-row">
            <div class="meal-info">
              <span class="time">${time}</span>
              <span class="meal-name">${meal.meal_name}</span>
              <span class="macros">${meal.calories} kcal | P:${meal.protein}g C:${meal.carbs}g F:${meal.fat}g</span>
            </div>
            <button class="delete-meal" data-meal-id="${meal.meal_id}" title="Delete this meal">
              <img src="../image/delete_icon.png" alt="Delete" class="delete-icon">
            </button>
          </div>
        `;
        logList.appendChild(li);
      });
    }
  }

  logList.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-meal');
    if (btn) {
      const mealId = btn.dataset.mealId;
      const mealItem = btn.closest('li');

      if (confirm('Delete this meal?')) {
        mealItem.remove();
        meals = meals.filter(meal => meal.meal_id !== parseInt(mealId));
        updateSummary();

        fetch('deleteMeal.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'delete_meal',
            meal_id: mealId
          })
        })
          .then(res => res.json())
          .then(data => {
            if (data.status !== 'success') {
              alert('Failed to delete meal from server.');
            }
          })
          .catch(err => {
            console.error('Delete error:', err);
          });
      }
    }
  });

  function renderProgressChart() {
    const dates = history.map(entry => entry.created_at || entry.meal_date);
    const calories = history.map(entry => parseInt(entry.calories));
    const protein = history.map(entry => parseInt(entry.protein));

    const ctx = document.getElementById('progressChart').getContext('2d');
    if (window.myChart) window.myChart.destroy();
    window.myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [
          { label: 'Calories', data: calories, borderColor: '#f44336', fill: false },
          { label: 'Protein', data: protein, borderColor: '#4CAF50', fill: false }
        ]
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
          title: { display: true, text: 'Calories & Protein Over Time' }
        }
      }
    });
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const meal = {
      username: username,
      meal_name: document.getElementById('meal-name').value,
      calories: parseInt(document.getElementById('calories').value),
      protein: parseInt(document.getElementById('protein').value),
      carbs: parseInt(document.getElementById('carbs').value),
      fat: parseInt(document.getElementById('fat').value),
      vitaminC: parseInt(document.getElementById('vitaminC').value) || 0,
    };

    const res = await fetch('addMeal.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(meal)
    });

    const result = await res.json();
    if (result.success) {
      alert('Meal saved!');
      form.reset();
      fetchMeals();
      fetchHistory(); // still useful for updating the chart
    } else {
      alert('Error saving meal.' + (result.error || 'Unknown error'));
    }
  });

  fetchMeals();
  fetchHistory();
});
