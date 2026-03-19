import pytest
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

# ─── Configuration ───────────────────────────────────────────────
BASE_URL = "http://127.0.0.1:8000"

TEST_NAME      = "Test Owner Selenium"
TEST_EMAIL     = f"selenium_{int(time.time())}@test.com"
TEST_PASSWORD  = "password123"
HOSTEL_NAME    = "Selenium Hostel"
HOSTEL_CITY    = "Tunis"
HOSTEL_COUNTRY = "Tunisie"


# ─── Fixture Driver ──────────────────────────────────────────────
@pytest.fixture(scope="session")
def driver():
    options = webdriver.ChromeOptions()
    # options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.add_argument("--window-size=1366,768")

    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service, options=options)
    driver.implicitly_wait(5)

    yield driver
    driver.quit()


def wait_for(driver, by, value, timeout=10):
    return WebDriverWait(driver, timeout).until(
        EC.visibility_of_element_located((by, value))
    )


def logout_user(driver):
    """Force la déconnexion proprement."""
    try:
        driver.get(f"{BASE_URL}/dashboard")
        time.sleep(1)
        logout_form = driver.find_element(By.CSS_SELECTOR, "form[action*='logout']")
        driver.execute_script("arguments[0].submit();", logout_form)
        time.sleep(2)
    except Exception:
        driver.delete_all_cookies()
        time.sleep(1)


# ═══════════════════════════════════════════════════════════════════
# TEST 1 — Page Register
# ═══════════════════════════════════════════════════════════════════
class TestRegisterPage:

    def test_register_page_loads(self, driver):
        """Vérifie que la page /register s'affiche correctement."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        assert "register" in driver.current_url or "Créer" in driver.title
        assert driver.find_element(By.NAME, "name")
        assert driver.find_element(By.NAME, "email")
        assert driver.find_element(By.NAME, "password")
        assert driver.find_element(By.NAME, "password_confirmation")

        print("✅ Page register accessible")

    def test_register_page_title(self, driver):
        """Vérifie le contenu principal de la page."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert "Créer" in body_text or "compte" in body_text.lower()

        print("✅ Titre page register correct")

    def test_register_empty_form_validation(self, driver):
        """Vérifie que le formulaire vide ne passe pas."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        submit = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
        submit.click()
        time.sleep(1)

        assert "register" in driver.current_url or "onboarding" not in driver.current_url

        print("✅ Validation formulaire vide fonctionne")

    def test_register_password_mismatch(self, driver):
        """Vérifie que les mots de passe différents sont rejetés."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        driver.find_element(By.NAME, "name").send_keys("Test User")
        driver.find_element(By.NAME, "email").send_keys("mismatch@test.com")
        driver.find_element(By.NAME, "password").send_keys("password123")
        driver.find_element(By.NAME, "password_confirmation").send_keys("different456")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(1)

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert "confirmation" in body_text.lower() or "register" in driver.current_url

        print("✅ Rejet mots de passe non identiques fonctionne")


# ═══════════════════════════════════════════════════════════════════
# TEST 2 — Inscription complète
# ═══════════════════════════════════════════════════════════════════
class TestRegisterFlow:

    def test_register_success(self, driver):
        """Inscription réussie avec données valides."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        driver.find_element(By.NAME, "name").clear()
        driver.find_element(By.NAME, "name").send_keys(TEST_NAME)

        driver.find_element(By.NAME, "email").clear()
        driver.find_element(By.NAME, "email").send_keys(TEST_EMAIL)

        driver.find_element(By.NAME, "password").clear()
        driver.find_element(By.NAME, "password").send_keys(TEST_PASSWORD)

        driver.find_element(By.NAME, "password_confirmation").clear()
        driver.find_element(By.NAME, "password_confirmation").send_keys(TEST_PASSWORD)

        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(2)

        assert "onboarding" in driver.current_url, \
            f"Attendu: /onboarding, Obtenu: {driver.current_url}"

        print(f"✅ Inscription réussie → {driver.current_url}")

    def test_register_duplicate_email(self, driver):
        """Vérifie que le même email est rejeté."""
        logout_user(driver)

        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        driver.find_element(By.NAME, "name").send_keys("Duplicate User")
        driver.find_element(By.NAME, "email").send_keys(TEST_EMAIL)
        driver.find_element(By.NAME, "password").send_keys(TEST_PASSWORD)
        driver.find_element(By.NAME, "password_confirmation").send_keys(TEST_PASSWORD)
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(1)

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert (
            "déjà" in body_text.lower()
            or "already" in body_text.lower()
            or "register" in driver.current_url
        )

        print("✅ Email dupliqué rejeté correctement")


# ═══════════════════════════════════════════════════════════════════
# TEST 3 — Onboarding
# ═══════════════════════════════════════════════════════════════════
class TestOnboarding:

    def test_onboarding_page_loads(self, driver):
        """Vérifie que la page onboarding s'affiche après inscription."""
        driver.get(f"{BASE_URL}/register")
        time.sleep(1)

        new_email = f"onboard_{int(time.time())}@test.com"

        driver.find_element(By.NAME, "name").clear()
        driver.find_element(By.NAME, "name").send_keys("Onboard Test")
        driver.find_element(By.NAME, "email").clear()
        driver.find_element(By.NAME, "email").send_keys(new_email)
        driver.find_element(By.NAME, "password").clear()
        driver.find_element(By.NAME, "password").send_keys(TEST_PASSWORD)
        driver.find_element(By.NAME, "password_confirmation").clear()
        driver.find_element(By.NAME, "password_confirmation").send_keys(TEST_PASSWORD)
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(2)

        assert "onboarding" in driver.current_url, \
            f"Attendu: /onboarding, Obtenu: {driver.current_url}"

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert "hostel" in body_text.lower()

        print("✅ Page onboarding accessible après inscription")

    def test_onboarding_create_hostel(self, driver):
        """Crée le premier hostel via onboarding."""
        assert "onboarding" in driver.current_url, \
            "Doit être sur /onboarding pour ce test"

        driver.find_element(By.NAME, "name").clear()
        driver.find_element(By.NAME, "name").send_keys(HOSTEL_NAME)

        driver.find_element(By.NAME, "city").clear()
        driver.find_element(By.NAME, "city").send_keys(HOSTEL_CITY)

        driver.find_element(By.NAME, "country").clear()
        driver.find_element(By.NAME, "country").send_keys(HOSTEL_COUNTRY)

        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(2)

        assert "dashboard" in driver.current_url, \
            f"Attendu: /dashboard, Obtenu: {driver.current_url}"

        print(f"✅ Hostel créé → {driver.current_url}")

    def test_dashboard_shows_hostel_name(self, driver):
        """Vérifie que le nom du hostel apparaît dans le dashboard."""
        assert "dashboard" in driver.current_url

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert HOSTEL_NAME in body_text

        print(f"✅ Dashboard affiche: {HOSTEL_NAME}")


# ═══════════════════════════════════════════════════════════════════
# TEST 4 — Déconnexion
# ═══════════════════════════════════════════════════════════════════
class TestLogout:

    def test_logout(self, driver):
        """Vérifie que la déconnexion fonctionne."""
        driver.get(f"{BASE_URL}/dashboard")
        time.sleep(1)

        logout_form = driver.find_element(By.CSS_SELECTOR, "form[action*='logout']")
        driver.execute_script("arguments[0].submit();", logout_form)
        time.sleep(2)

        assert "login" in driver.current_url or "register" in driver.current_url, \
            f"Attendu: /login, Obtenu: {driver.current_url}"

        print(f"✅ Déconnexion réussie → {driver.current_url}")

    def test_protected_route_after_logout(self, driver):
        """Vérifie qu'on ne peut pas accéder au dashboard après déconnexion."""
        driver.get(f"{BASE_URL}/dashboard")
        time.sleep(1)

        assert "login" in driver.current_url, \
            f"Devrait rediriger vers /login, mais: {driver.current_url}"

        print("✅ Route protégée redirige vers /login après déconnexion")


# ═══════════════════════════════════════════════════════════════════
# TEST 5 — Connexion
# ═══════════════════════════════════════════════════════════════════
class TestLogin:

    def test_login_page_loads(self, driver):
        """Vérifie que la page login s'affiche."""
        driver.get(f"{BASE_URL}/login")
        time.sleep(1)

        assert driver.find_element(By.NAME, "email")
        assert driver.find_element(By.NAME, "password")

        print("✅ Page login accessible")

    def test_login_wrong_credentials(self, driver):
        """Vérifie que les mauvais identifiants sont rejetés."""
        driver.get(f"{BASE_URL}/login")
        time.sleep(1)

        driver.find_element(By.NAME, "email").clear()
        driver.find_element(By.NAME, "email").send_keys("wrong@test.com")
        driver.find_element(By.NAME, "password").clear()
        driver.find_element(By.NAME, "password").send_keys("wrongpassword")
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(1)

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert (
            "incorrect" in body_text.lower()
            or "identifiant" in body_text.lower()
            or "login" in driver.current_url
        )

        print("✅ Mauvais identifiants rejetés")

    def test_login_success(self, driver):
        """Connexion réussie avec les bons identifiants."""
        driver.get(f"{BASE_URL}/login")
        time.sleep(1)

        driver.find_element(By.NAME, "email").clear()
        driver.find_element(By.NAME, "email").send_keys(TEST_EMAIL)
        driver.find_element(By.NAME, "password").clear()
        driver.find_element(By.NAME, "password").send_keys(TEST_PASSWORD)
        driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        time.sleep(2)

        assert "dashboard" in driver.current_url or "onboarding" in driver.current_url, \
            f"Attendu: /dashboard ou /onboarding, Obtenu: {driver.current_url}"

        print(f"✅ Connexion réussie → {driver.current_url}")

    def test_login_redirects_if_already_logged(self, driver):
        """Vérifie que la page /login répond sans erreur si connecté."""
        driver.get(f"{BASE_URL}/login")
        time.sleep(1)

        body_text = driver.find_element(By.TAG_NAME, "body").text
        assert "500" not in body_text and "Server Error" not in body_text

        print("✅ Page /login accessible sans erreur même si connecté")


# ═══════════════════════════════════════════════════════════════════
# TEST 6 — Sécurité
# ═══════════════════════════════════════════════════════════════════
class TestSecurity:

    def test_csrf_token_present(self, driver):
        """Vérifie que le token CSRF est présent dans les formulaires."""
        driver.get(f"{BASE_URL}/login")
        time.sleep(1)

        csrf = driver.find_element(By.NAME, "_token")
        assert csrf.get_attribute("value") != ""

        print("✅ Token CSRF présent dans le formulaire login")

    def test_dashboard_requires_auth(self, driver):
        """Vérifie que /dashboard nécessite une authentification."""
        try:
            driver.get(f"{BASE_URL}/dashboard")
            time.sleep(1)
            logout_form = driver.find_element(By.CSS_SELECTOR, "form[action*='logout']")
            driver.execute_script("arguments[0].submit();", logout_form)
            time.sleep(2)
        except Exception:
            pass

        driver.delete_all_cookies()
        time.sleep(1)

        driver.get(f"{BASE_URL}/dashboard")
        time.sleep(1)

        assert "login" in driver.current_url, \
            f"Attendu: /login, Obtenu: {driver.current_url}"

        print("✅ /dashboard protégé par authentification")

    def test_rooms_requires_auth(self, driver):
        """Vérifie que /rooms nécessite une authentification."""
        driver.delete_all_cookies()
        time.sleep(1)

        driver.get(f"{BASE_URL}/rooms")
        time.sleep(1)

        assert "login" in driver.current_url, \
            f"Attendu: /login, Obtenu: {driver.current_url}"

        print("✅ /rooms protégé par authentification")