let map;

// Initialize map
map = L.map('map').setView([12.9716, 77.5946], 13);

// OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Show user location
map.locate({ setView: true, maxZoom: 16 });
map.on('locationfound', function(e) {
  L.marker([e.latitude, e.longitude]).addTo(map)
    .bindPopup("You are here!").openPopup();

  document.getElementById('lat').value = e.latitude;
  document.getElementById('lng').value = e.longitude;
});

// Fetch and show hazards
async function loadHazards() {
  try {
    const res = await fetch('fetch_reports.php');
    const data = await res.json();

    data.forEach(h => {
      const marker = L.marker([h.lat, h.lng]).addTo(map)
        .bindPopup(`
          <strong>${h.type}</strong><br>
          ${h.verified == 1 ? "✅ Verified" : "❌ Not Verified"}<br>
          ${h.photo ? `<img src="${h.photo}" width="150">` : ""}
        `);

      marker.on('click', () => map.setView([h.lat, h.lng], 16));
    });
  } catch(err) {
    console.error("Error loading hazards:", err);
  }
}

// Initial load + auto-refresh every 5 seconds
loadHazards();
setInterval(loadHazards, 5000);

// Handle form submission
document.getElementById('reportForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const lat = document.getElementById('lat').value;
  const lng = document.getElementById('lng').value;
  const type = document.getElementById('type').value;
  const photo = document.getElementById('photo').files[0];

  const formData = new FormData();
  formData.append('lat', lat);
  formData.append('lng', lng);
  formData.append('type', type);
  if (photo) formData.append('photo', photo);

  try {
    const res = await fetch('upload.php', { method: 'POST', body: formData });
    const msg = await res.text();
    alert(msg);
    loadHazards();
    document.getElementById('reportForm').reset();
  } catch(err) {
    alert("Error submitting hazard: " + err);
  }
});

// SOS Button functionality
// SOS Button functionality (WhatsApp only)
document.getElementById('sosBtn').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // WhatsApp message
            const message = `⚠️ I need help! My location: https://www.google.com/maps?q=${lat},${lng}`;

            // Open WhatsApp with pre-filled message
            const whatsappLink = `https://wa.me/919400243533?text=${encodeURIComponent(message)}`;

            window.open(whatsappLink, '_blank');

        }, () => {
            alert("Unable to get your location. Please enable GPS.");
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
});

const loginModal = document.getElementById("loginModal");
const loginBtn = document.getElementById("loginBtn");
const closeBtn = document.querySelector(".modal .close");

loginBtn.onclick = () => { loginModal.style.display = "block"; }
closeBtn.onclick = () => { loginModal.style.display = "none"; }

window.onclick = (event) => {
  if (event.target == loginModal) loginModal.style.display = "none";
}

// Handle login form
document.getElementById("loginForm").addEventListener("submit", async (e)=>{
  e.preventDefault();
  const formData = new FormData(e.target);

  const res = await fetch("login.php", { method:"POST", body:formData });
  const text = await res.text();

  if(text.trim() === "success"){
    alert("Login successful & emergency contact saved!");
    loginModal.style.display = "none";
  } else {
    alert(text);
  }
});




// Close modal when clicking outside
window.onclick = (event) => {
  if (event.target == loginModal) loginModal.style.display = "none";
};


