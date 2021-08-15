import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { UserService, AdminService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class PricingPlanDetailByIdResolver implements Resolve<any> {
  constructor(
    private adminService: AdminService,
    private router: Router
  ) {
    console.log('APPLY');
  }
  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> {
    return this.adminService.getPricingPlanDetails({pricing_plan_guid: route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('/console/')));
  }
}
