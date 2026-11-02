document.addEventListener('DOMContentLoaded', function() {
    // Navigation between sections
    const navLinksSection = document.querySelectorAll('.section-nav a');
    navLinksSection.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinksSection.forEach(l => l.classList.remove('active'));
            document.querySelectorAll('section').forEach(s => s.classList.remove('active-section'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            const targetSection = document.getElementById(this.dataset.section);
            if (targetSection) {
                targetSection.classList.add('active-section');
            } else {
                console.warn(`No section found with ID: ${this.dataset.section}`);
            }
        });
    });

    function getLocalDateTimeString() {
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ` +
           `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    }

    // Utility
    const getWeekdayIndex = dateStr => (new Date(dateStr).getDay());

    
    // Initialize charts
    const waterChart = new Chart(document.getElementById('water-chart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Water Intake (ml)',
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(124, 197, 118, 0.7)',
                borderColor: 'rgba(124, 197, 118, 1)',
                borderWidth: 1
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    const sleepChart = new Chart(document.getElementById('sleep-chart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Hours Slept',
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(86, 171, 47, 0.2)',
                borderColor: 'rgba(86, 171, 47, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: { scales: { y: { beginAtZero: true, max: 10 } } }
    });



    
    // Exercise Recommendations
    const workoutBtn = document.getElementById('get-workout');
    const workoutResult = document.getElementById('workout-result');
    const workoutHistory = document.getElementById('workout-history');
    
    const workoutPlans = {
        weight_loss: [
            "30 minutes of brisk walking or jogging",
            "20 minutes of HIIT (High Intensity Interval Training)",
            "Bodyweight exercises: 3 sets of 15 squats, 10 push-ups, 20 lunges"
        ],
        muscle_gain: [
            "Weight training: 4 sets of 8-12 reps for major muscle groups",
            "Focus on compound movements: bench press, squats, deadlifts",
            "Rest 60-90 seconds between sets"
        ],
        endurance: [
            "45-60 minutes of moderate cardio (running, cycling, swimming)",
            "Circuit training with minimal rest between exercises",
            "Try interval training: 1 minute sprint, 2 minutes walk, repeat 10 times"
        ],
        flexibility: [
            "30 minutes of yoga or pilates",
            "Dynamic stretching before workout, static stretching after",
            "Focus on major muscle groups: hamstrings, shoulders, hips"
        ]
    };
    
    workoutBtn.addEventListener('click', () => {
        const goal = document.getElementById('goal').value;
        const plan = workoutPlans[goal];
        let html = `<h3>Your ${goal.replace('_', ' ')} Workout Plan:</h3><ul>`;
        plan.forEach(p => html += `<li>${p}</li>`);
        html += '</ul>';
        workoutResult.innerHTML = html;

        
        // Save to history
        const now = new Date();
        const li = document.createElement('li');
        li.innerHTML = `<strong>${now.toLocaleDateString()} ${now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</strong>: ${goal.replace('_', ' ')} workout`;
        workoutHistory.prepend(li);

        saveWorkout(goal, plan.join(', '), getLocalDateTimeString());
    });

    
    // Water Intake Tracker
    const waterGlass = document.getElementById('water-fill');
    const waterToday = document.getElementById('water-today');
    const waterTarget = document.getElementById('water-target');
    const addWaterBtn = document.getElementById('add-water');
    const waterAmount = document.getElementById('water-amount');
    
    let currentWater = 0;
    const targetAmount = 2000; // ml
    waterTarget.textContent = targetAmount;
    
    const updateWaterDisplay = () => {
        const percent = (currentWater / targetAmount) * 100;
        waterGlass.style.height = `${percent}%`;
        waterGlass.style.backgroundColor = percent >= 100 ? '#56AB2F' : '#7CC576';
        waterToday.textContent = currentWater;
    };

    addWaterBtn.addEventListener('click', () => {
        const amount = parseInt(waterAmount.value) || 0;
        if (amount > 0) {
            currentWater += amount;
            if (currentWater > targetAmount) currentWater = targetAmount;
            updateWaterDisplay();
            const index = getWeekdayIndex(new Date());
            waterChart.data.datasets[0].data[index] = currentWater;
            waterChart.update();
            saveWaterIntake(amount);
            waterAmount.value = '';
        }
    });

    
    // Sleep Monitoring
    const logSleepBtn = document.getElementById('log-sleep');
    const sleepHours = document.getElementById('sleep-hours');
    const sleepFeedback = document.getElementById('sleep-feedback');
    const sleepTipsList = document.getElementById('sleep-tips');
    
    const sleepTips = [
        "Maintain a consistent sleep schedule, even on weekends.",
        "Create a relaxing bedtime routine.",
        "Make your bedroom quiet, dark, and cool.",
        "Limit screen time before bed.",
        "Avoid large meals, caffeine, and alcohol before bedtime.",
        "Exercise regularly, but not too close to bedtime.",
        "Manage stress with meditation or deep breathing exercises."
    ];
    
    // Display sleep tips
    sleepTips.forEach(tip => {
        const li = document.createElement('li');
        li.textContent = tip;
        sleepTipsList.appendChild(li);
    });
    
    const generateSleepFeedback = (newInput, current) => {
        const total = (current || 0) + newInput;

	if (total < 6)
            return `<p class="warning">You slept a total of ${total} hours today. Adults typically need 7–9 hours per night.</p>`;
	if (total < 7)
            return `<p class="warning">You're at ${total} hours so far—try to get a little more rest.</p>`;
 	if (total <= 9)
            return `<p class="success">Great! Your total today is ${total} hours—right on target.</p>`;
            return `<p class="info">You've slept ${total} hours today. Oversleeping can sometimes indicate stress or fatigue—listen to your body.</p>`;
    };

    logSleepBtn.addEventListener('click', () => {
        const hours = parseFloat(sleepHours.value);
        if (hours >= 0 && hours <= 24) {
            sleepFeedback.innerHTML = generateSleepFeedback(hours);
            const index = getWeekdayIndex(new Date());
	    const current = sleepChart.data.datasets[0].data[index] || 0;
	    const total = current + hours;
	    sleepFeedback.innerHTML = generateSleepFeedback(hours, current);


            sleepChart.data.datasets[0].data[index] = current + hours;
            sleepChart.update();
            saveSleepData(current + hours);
            sleepHours.value = '';
        } else {
            sleepFeedback.innerHTML = '<p class="error">Please enter a valid number between 0–24.</p>';
        }
    });
    
    // Simulated backend functions
    function saveWorkout(goal, plan, dateTime) {
        fetch('backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'save_workout',
                goal,
                plan,
                date: dateTime
            })
        });
    }
    
    function saveWaterIntake(amount) {
        fetch('backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'save_water',
                amount,
                date: new Date().toISOString()
            })
        });
    }

    
    function saveSleepData(hours) {
        fetch('backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'save_sleep',
                hours,
                date: new Date().toISOString()
            })
        });
    }

    document.getElementById('workout-history').addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.delete-workout');
	if (deleteBtn) {
            const username = deleteBtn.dataset.username;
            const date = deleteBtn.dataset.date;

            fetch('backend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'delete_workout',
                    username,
                    date
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    deleteBtn.closest('.workout-entry').remove();
                } else {
                    alert('Failed to delete workout.');
                }
            })
	    .catch(err => {
		console.error('Delete request failed:', err);
		alert('An error occurred while deleting.');
	    });
        }
    });
    
    // Load initial data
    function loadInitialData() {
        fetch('backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_history' })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {

        	// 🏋️ Render workout history
	        if (data.workout_data && Array.isArray(data.workout_data)) {
		    const workoutHistory = document.getElementById('workout-history')
        	    workoutHistory.innerHTML = '';

                    const grouped = {};
	            data.workout_data.forEach(entry => {
	                const date = new Date(entry.date).toLocaleDateString();
			if (!grouped[date]) grouped[date] = [];
			grouped[date].push(entry);
		    });

		    for (const date in grouped) {
		        const dateHeader = document.createElement('li');
			dateHeader.innerHTML = `<strong>${date}</strong>`;
			workoutHistory.appendChild(dateHeader);
		
		        grouped[date].forEach(entry => {
		            const time = new Date(entry.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
 			    const li = document.createElement('li');
      			    li.classList.add('workout-entry');
			    li.innerHTML = `
        			<div class="workout-row">
			            <div class="workout-info">
			                <span class="time">${time}</span>
			                <span class="goal">${entry.goal.replace('_', ' ')}</span>
			                <span class="plan">${entry.plan}</span>
			            </div>
		                    <button class="delete-workout" data-username="${entry.username}" data-date="${entry.date}" title="Delete">
				        <img src="../image/delete_icon.png" alt="Delete" class="delete-icon">
				    </button>
			        </div>
                            `;
                            workoutHistory.appendChild(li);
                        });
                    }
                }

                    const w = [0, 0, 0, 0, 0, 0, 0];
                    const s = [0, 0, 0, 0, 0, 0, 0];

                    for (let day in data.water_data) {
                        const i = getWeekdayIndex(day);
                        w[i] = data.water_data[day];
                    }

                    for (let day in data.sleep_data) {
                        const i = getWeekdayIndex(day);
                        s[i] = data.sleep_data[day];
                    }

                    waterChart.data.datasets[0].data = w;
                    sleepChart.data.datasets[0].data = s;
                    waterChart.update();
                    sleepChart.update();

                    // Restore current water for today
		    const todayIndex = new Date().getDay();
		    currentWater = waterChart.data.datasets[0].data[todayIndex];
		    updateWaterDisplay();

                } else {
                    console.error('History load error:', data.message);
                }
            })
            .catch(err => console.error('Fetch error:', err));
    }

    // Fire on load
    loadInitialData();
});
