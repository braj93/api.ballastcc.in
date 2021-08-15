import { Injectable, } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { CampaignService } from '../../core';
import { catchError } from 'rxjs/operators';

@Injectable()
export class CampaignCallTrackingResolver implements Resolve<any> {
  constructor(
    private campaignService: CampaignService,
    private router: Router
  ) {}
  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> {
    return this.campaignService.getTrackerDetails({campaign_id :route.params['id']})
    .pipe(catchError((err) => this.router.navigateByUrl('/user/')));
  }
}
