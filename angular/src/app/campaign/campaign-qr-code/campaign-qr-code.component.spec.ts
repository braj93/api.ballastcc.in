import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CampaignStepTwoComponent } from './campaign-qr-code.component';

describe('CampaignStepTwoComponent', () => {
  let component: CampaignStepTwoComponent;
  let fixture: ComponentFixture<CampaignStepTwoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CampaignStepTwoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CampaignStepTwoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
