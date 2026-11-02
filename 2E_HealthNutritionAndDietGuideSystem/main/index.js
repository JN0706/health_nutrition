document.addEventListener("DOMContentLoaded", () => {
  // Links require login 
  const links = document.querySelectorAll(".protected");

  // Highlight the active link based on current URL
  const currentPath = window.location.pathname;

  links.forEach(link => {
    const target = link.dataset.destination;
    const resolvedTarget = new URL(target, window.location.origin).pathname;

    if (currentPath.endsWith(resolvedTarget)) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }

    // Handle click and session check
    link.addEventListener("click", async (e) => {
      e.preventDefault();

      try {
        const res = await fetch("check_session.php");
        const { loggedIn } = await res.json();

        if (loggedIn) {
          window.location.href = target;
        } else {
          window.location.href = `../login/login.html?redirect=${encodeURIComponent(target)}`;
        }
      } catch {
        window.location.href = "../login/login.html";
      }
    });
  });
});
