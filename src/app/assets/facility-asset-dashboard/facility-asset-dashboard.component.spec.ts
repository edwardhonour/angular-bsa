import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FacilityAssetDashboardComponent } from './facility-asset-dashboard.component';

describe('FacilityAssetDashboardComponent', () => {
  let component: FacilityAssetDashboardComponent;
  let fixture: ComponentFixture<FacilityAssetDashboardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FacilityAssetDashboardComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(FacilityAssetDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
