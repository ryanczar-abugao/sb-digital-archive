import Base from './src/base.js';
import { Pages } from './src/constants.js';
import { Components } from './src/constants.js';
import { AppHtmlNodes } from './src/constants.js';
import SessionService from './src/services/sessionService.js';

// Abstract the implementation of Base Class
class App extends Base {
    constructor(sessionService) {
        super();
        this.session = sessionService.getSession();
    }

    getSessionState() {
        let sessionService = new SessionService();
        let session = sessionService.getSession();
        return session.isSessionSet;
    }
};

$(document).ready(function () {
    let sessionService = new SessionService();
    let app = new App(sessionService);
    let appSession = app.session;

    const appHtmlNodes = new AppHtmlNodes();
    const pages = new Pages();
    const components = new Components();

    $(appHtmlNodes.ids.root).load(pages.files.mainLayout, function () {
        if (appSession.isSessionSet) {
            app.loadComponent(appHtmlNodes.ids.sidebar, components.files.sidebar);
            app.removeComponent(appHtmlNodes.ids.footer);        
        } else {
            app.removeComponent(appHtmlNodes.ids.sidebar);        
            app.loadComponent(appHtmlNodes.ids.footer, components.files.footer);
        }

        app.loadComponent(appHtmlNodes.ids.topNavbar, components.files.topNavbar);

        // TODO: Dynamically Render Body
        app.loadComponent(appHtmlNodes.ids.body, pages.files.ordinances);
    });
});
