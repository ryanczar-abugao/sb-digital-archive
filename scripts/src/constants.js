class BasePath {
  constructor(prefix) {
    this.prefix = prefix;
  }

  getPath(file) {
    return `${this.prefix}${file}`;
  }
}

export class Pages extends BasePath {
  constructor() {
    super('./pages/');
    this.files = {
      mainLayout: this.getPath('mainLayout.html'),
      home: this.getPath('home.html'),
      aboutUs: this.getPath('aboutUs.html'),
      ordinances: this.getPath('ordinances.html')
    };
  }
}

export class Components extends BasePath {
  constructor() {
    super('./components/');
    this.files = {
      sidebar: this.getPath('sidebar.html'),
      footer: this.getPath('footer.html'),
      topNavbar: this.getPath('topNavbar.html'),
      history: this.getPath('aboutUs/history.html'),
      sbMembers: this.getPath('aboutUs/sbMembers.html')
    };
  }
}

export class AppHtmlNodes {
  constructor() {
    this.ids = {
      root: '#root',
      topNavbar: '#topNavbar',
      sidebar: '#sidebar',
      footer: '#footer',
      body: '#body',
      logo: '#logo',
      systemName: '#systemName',
    };
  }
}
