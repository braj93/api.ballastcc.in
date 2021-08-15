import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CampaignSettingComponent } from './campaign-setting.component';

describe('CampaignSettingComponent', () => {
  let component: CampaignSettingComponent;
  let fixture: ComponentFixture<CampaignSettingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CampaignSettingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CampaignSettingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
