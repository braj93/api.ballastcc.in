/*!

=========================================================
* Material Dashboard Angular - v2.4.0
=========================================================

* Product Page: https://www.-.com/product/material-dashboard-angular2
* Copyright 2019   (https://www.-.com)
* Licensed under MIT (https://github.com/material-dashboard-angular2/blob/master/LICENSE.md)

* Coded by  

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app/app.module';
import { environment } from './environments/environment';
import 'hammerjs';

if (environment.production) {
  enableProdMode();
}

platformBrowserDynamic().bootstrapModule(AppModule);
