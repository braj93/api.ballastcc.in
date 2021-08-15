import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { AdminService, CrmService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ContactDetailResolver implements Resolve<any> {
  constructor(
    private crmService: CrmService,
    private router: Router
  ) {}
  resolve(
    route: ActivatedRouteSnapshot, 
    state: RouterStateSnapshot): Observable<any> {
    return this.crmService.getDetailsById({crm_contact_id :route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('/user/')));
  }
}
