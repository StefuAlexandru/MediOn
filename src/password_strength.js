function checkPasswordStrength() {
    const password = document.getElementById("password").value;
    const strengthBar = document.getElementById("strength-bar");
  
    let score = 0;
    if (password.length >= 8) {
      score += 1;
    }
    if (/[a-z]/.test(password)) {
      score += 1;
    }
    if (/[A-Z]/.test(password)) {
      score += 1;
    }
    if (/[0-9]/.test(password)) {
      score += 1;
    }
    if (/[^a-zA-Z0-9]/.test(password)) {
      score += 1;
    }
  
  
    const width = (score / 5) * 100; 
    strengthBar.style.width = `${width}%`;

  }