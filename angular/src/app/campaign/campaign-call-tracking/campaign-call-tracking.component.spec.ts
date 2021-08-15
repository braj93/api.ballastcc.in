import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CampaignCallTrackingComponent } from './campaign-call-tracking.component';

describe('CampaignCallTrackingComponent', () => {
  let component: CampaignCallTrackingComponent;
  let fixture: ComponentFixture<CampaignCallTrackingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CampaignCallTrackingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CampaignCallTrackingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
