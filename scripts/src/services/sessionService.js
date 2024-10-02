class Session {
  name = null;
  position = null;
  sessionTimeout = null;
  sessionExpiration = null;
  isSessionSet = false;
}

export default class SessionService {
  getSession() {
    let session = new Session();

    session = {
      name: sessionStorage.getItem("name"),
      position: sessionStorage.getItem("position"),
      sessionTimeout: sessionStorage.getItem("sessionTimeout"),
      sessionExpiration: sessionStorage.getItem("sessionExpiration"),
      isSessionSet: sessionStorage.getItem("isSessionSet"),
    }

    return session;
  };

  setSession(session) {
    sessionStorage.setItem("name", session.name);
    sessionStorage.setItem("position", session.position);
    sessionStorage.setItem("sessionTimeout", session.sessionTimeout);
    sessionStorage.setItem("sessionExpiration", session.sessionExpiration);
    sessionStorage.setItem("isSessionSet", session.isSessionSet);
  };

  clearSession() {
    sessionStorage.clear();
  }
}
