import { Injectable, Injector } from '@angular/core';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';

import { JwtService } from '../services';

@Injectable()
export class HttpTokenInterceptor implements HttpInterceptor {
  constructor(private jwtService: JwtService) {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let headersConfig;
    if(req.url == `${environment.api_url}`+'/uploads' || req.url == `${environment.api_url}`+'/uploads/bulk' || req.url == `${environment.api_url}`+'/uploads/index'){
      headersConfig = {};
    }else{
      headersConfig = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      };
    }
    
    const token = this.jwtService.getToken();

    if (token) {
      headersConfig['session-key'] = `${token}`;
    }

    const request = req.clone({ setHeaders: headersConfig });
    return next.handle(request);
  }
}
