import { ChangeDetectionStrategy, Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subject, takeUntil } from 'rxjs';
import { FuseMediaWatcherService } from '@fuse/services/media-watcher';
import { FuseNavigationService, FuseVerticalNavigationComponent } from '@fuse/components/navigation';
import { Navigation } from 'app/core/navigation/navigation.types';
import { NavigationService } from 'app/core/navigation/navigation.service';
import { DataService } from 'app/data.service';
import { FormBuilder } from '@angular/forms';
import {  FormControl, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';


@Component({
  selector: 'app-facility-asset-dashboard',
  templateUrl: './facility-asset-dashboard.component.html',
  styleUrls: ['./facility-asset-dashboard.component.scss']
})
export class FacilityAssetDashboardComponent implements OnInit, OnDestroy {
  navigation: Navigation;
  isScreenSmall: boolean;
  term: any;
  p: any;
  formFieldHelpers: string[] = [''];
  uploadData: any;

    data: any;
    selectedProject: string = 'ACME Corp. Backend App';
    private _unsubscribeAll: Subject<any> = new Subject<any>();
    currentYear: any;
    email: any;
      //Upload 
    index: any;
    user: any;
    adding: any;
    addcomponents: any;
    employee_name: any;
    dob: any;
    editing: any;
    editQQ: any;
    uploading: any;
    dsc: any;
    doc_title: any;
    panelID: any = [];
    panelOpen: any = [];
    testing: any;
    posting: any;
    doctype: any;
    stddoc: any;
    title: any;
    assetid: any;

    showingFacility: any;

    /**
     * Constructor
     */

     constructor(
      private _activatedRoute: ActivatedRoute,
      private _router: Router,
      private _navigationService: NavigationService,
      private _fuseMediaWatcherService: FuseMediaWatcherService,
      private _fuseNavigationService: FuseNavigationService,
      private _dataService: DataService,
      private _formBuilder: FormBuilder,
      public http: HttpClient  // used by upload
  ) { }

    showFacility() {
      if (this.showingFacility=='N') {
        this.showingFacility='Y'
      } else {
        this.showingFacility='N'
      }
    }

    showPanel(id: any) {
      //
      // BARCODE is an unused asset column we are using to determine
      // if a panel is open or closed.
      //
      this.data.pacs.forEach(function (value: any) {
        if (value.ASSET_ID==id) {
          if (value.BARCODE=='Y') {
            value.BARCODE='N';
          } else {
            value.BARCODE='Y';
          }
        }
      });
    }

    showComponentEdit(all:any, n: any) {
      //
      // BARCODE is an unused asset column we are using to determine
      // if a panel is open or closed.
      //
      this.data.formData=n;
      
      all.forEach(function (value: any) {
        if (value.ASSET_ID==n.ASSET_ID) {
          if (value.BARCODE=='Y') {
            value.BARCODE='N';
          } else {
            value.BARCODE='Y';
          }
        }
      });
    }

    showTest(id: any) {
      if (this.testing=='N') {
          this.testing='Y'
          this.editing='N';
          this.addcomponents='N'
          this.data.formData2['ASSET_ID']=id;
          this.data.formData2['TEST_DATE']="";
          this.data.formData2['TITLE']="";
          this.data.formData2['RESULTS']="ADEQUATE";
          this.data.formData2['REASON']="";
      } else {
          this.testing='N'
          this.data.formData2['ASSET_ID']=id;
          this.data.formData2['TEST_DATE']=''
          this.data.formData2['TITLE']="";
          this.data.formData2['RESULTS']="ADEQUATE";
          this.data.formData2['REASON']="";
      }
    }

    showAddComponents(id: any, c: any) {
       if (this.addcomponents=='Y') {
            this.addcomponents='N';
       } else {
            this.addcomponents='Y';
            this.testing='N'
            this.editing='N';
            this.data.formData3.ASSET_ID=id;
            this.data.formData3.ASSET_NAME="";
            this.data.formData3.ASSET_TYPE="";
            this.data.formData3.ASSET_CLASS=c;
            this.data.formData3.QTY="";
            this.data.formData3.MFG="";
            this.data.formData3.MODEL="";
            this.data.formData3.SERIAL="";
       }

    }


    showEdit(id) {
      if (this.editing=='Y') {
           this.editing='N';
           // 
           // Clear formData when the panel is closed.
           //
           this.data.formData['ASSET_ID']="";
           this.data.formData['FACILITY_ID']="";
           this.data.formData['BUILDING_NBR']="";
           this.data.formData['ASSET_TYPE']="";
           this.data.formData['POST_ID']="";
           this.data.formData['AGENCY_CODE']="";
           this.data.formData['LOCATION_DSC']="";
           this.data.formData['MFG']="";
           this.data.formData['MODEL']="";
           this.data.formData['SERIAL']="";
           this.data.formData['INVENTORY_NUM']="";
           this.data.formData['XR_TAG']="";
           this.data.formData['COST']="";
           this.data.formData['CONTRACT_NUM']="";
           this.data.formData['WARRANTY_END_DT']="";
           this.data.formData['LIFE_END_DT']="";
           this.data.formData['SERVICE_VENDOR_ID']="";
           this.data.formData['LAST_SERVICE_DT']="";
           this.data.formData['LAST_SERVICE_DSC']="";
           this.data.formData['INSTALL_DT']="";
           this.data.formData['EXCESS_DT']="";     
           this.data.formData['EXCESS_REASON']="";
           this.data.formData['LAST_INSP_DT']="";
           this.data.formData['LAST_INSP_RESULT']="";
           this.data.formData['NOTE']="";
           this.data.formData['STATUS']="";
           this.data.formData['NEXT_SERVICE_DT']="";
           this.data.formData['NEXT_SERVICE_DSC']="";
           this.data.formData['OWNERSHIP']="";
           this.data.formData['AGENCY_POC']="";
           this.data.formData['DESCRIPTION']="";
           this.data.formData['SERVICE_VENDOR_NAME']="";
           this.data.formData['ADD_USER_ID']="";
           this.data.formData['ADD_DT']="";
           this.data.formData['DEL_USER_ID']="";
           this.data.formData['DEL_DT']="";
           this.data.formData['DISPOSAL_SERIAL']="";
           this.data.formData['SUNFLOWER_NUM']="";
           this.data.formData['POC_NAME']="";
           this.data.formData['POC_NUMBER']="";
           this.data.formData['POC_EMAIL']="";
           this.data.formData['REGION_ID']="";
           this.data.formData['ORIGINAL_ORDER']="";
           this.data.formData['CURRENT_TASK_ORDER']="";
           this.data.formData['SALES_ORDER']="";
           this.data.formData['LEASE_CONTRACT']="";        
           this.data.formData['ADDRESS']="";
           this.data.formData['CITY']="";
           this.data.formData['ENTRANCE_TABLE']="";
           this.data.formData['EXIT_TABLE']="";
           this.data.formData['ORDER_DT']="";
           this.data.formData['POP_START_DT']="";
           this.data.formData['POP_EXPIRE_DT']="";
           this.data.formData['EST_DELIV_DT']="";
           this.data.formData['TASK_ORDER_STATUS']="";
           this.data.formData['LOCATION_VER']="";
           this.data.formData['LOAD_STATUS']="";
           this.data.formData['ORIGINAL_MASTER']="";
           this.data.formData['BARCODE']="";
           this.data.formData['OFFICIAL_NAME']="";
           this.data.formData['STOCK_NUMBER']="";
           this.data.formData['FLAGS']="";
           this.data.formData['UNIQUE_NAME']="";
           this.data.formData['STEWARD']="";
           this.data.formData['COMPONENT']="";
           this.data.formData['OWNER']="";
           this.data.formData['CUSTODIAN']="";
           this.data.formData['ACTIVITY_STATUS']="";
           this.data.formData['ASSET_CONDITIION']="";
           this.data.formData['CONDITION_DESCRIPTION']="";
           this.data.formData['SITE']="";
           this.data.formData['CONTAINER']="";
           this.data.formData['RESOLUTION ']="";
           this.data.formData['AGREEMENT']="";
           this.data.formData['DOCUMENT_TYPE']="";
           this.data.formData['DOC_NUM']="";
           this.data.formData['STORAGE_TYPE']="";
           this.data.formData['ORGANIZATION']="";
           this.data.formData['INITIAL_EVENT']="";
           this.data.formData['MODEL_NAME']="";
           this.data.formData['PHYSICAL_INVENTORY_DATE']="";
           this.data.formData['PHYSICAL_INVENTORY_DT']="";
           this.data.formData['COMPONENT_FLAG']="";
           this.data.formData['COMPONENT_ID']="";
           this.data.formData['ASSET_SUB_TYPE']="";
           this.data.formData['ASSET_CLASS']="";
           this.data.formData['ASSET_NAME']="";
           this.data.formData['ACTIVE_FLAG']="";
           this.data.formData['ATTR_ANALOG']="";
           this.data.formData['ATTR_DIGITAL']="";
           this.data.formData['ATTR_IP']="";
           this.data.formData['ATTR_FIXED']="";
           this.data.formData['ATTR_PTZ']="";
           this.data.formData['ATTR_BW']="";
           this.data.formData['ATTR_INFRARED']="";
           this.data.formData['ATTR_HSPD12']="";
           this.data.formData['ATTR_APL_LIST']="";
           this.data.formData['ATTR_CRASH_RATED']="";
           this.data.formData['CREATE_TIME']="";
           this.data.formData['UPLOADED_BY']="";
           this.data.formData['MAINT_CONTRACT']="";
           this.data.formData['MAINT_VENDOR']="";
           this.data.formData['AGE']="";
           this.data.formData['COMPONENT_COUNT']="";
           this.data.formData['COMPONENT_WORKING']="";
           this.data.formData['COMPONENT_NEED_REPAIR']="";
           this.data.formData['AGENCY_RESPONSIBILITY']="";
           this.data.formData['LESSOR_NAME']="";
           this.data.formData['IS_SYSTEM']="";
           this.data.formData['COUNT_PTZ']="";
           this.data.formData['COUNT_FIXED']="";
           this.data.formData['COUNT_IP']="";
           this.data.formData['COUNT_MONITORS']="";
           this.data.formData['COUNT_DVR']="";
           this.data.formData['COUNT_CONTROLLERS']="";
           this.data.formData['HAS_UPS']="";
           this.data.formData['RECORDING_TYPE']="";
           this.data.formData['CONTROLLER_TYPE']="";
           this.data.formData['PEN_RATING']="";
           this.data.formData['ITEM_COUNT']="";
           this.data.formData['VERIFIED']="";
           this.data.formData['VERIFIED_BY']="";
           this.data.formData['VERIFIED_DT']="";
           this.data.formData['MFG_ID']="";
           this.data.formData['MODEL_ID']="";
           this.data.formData['R2_ASSET_TYPE_ORIG']="";
           this.data.formData['CREATE_DATE']="";
           this.data.formData['COUNT_IDS_CONTROL_PANEL']="";
           this.data.formData['COUNT_IDS_POWER_SUPPLY']="";
           this.data.formData['COUNT_IDS_UPS']="";
           this.data.formData['COUNT_IDS_USER_INTERFACE']="";
           this.data.formData['COUNT_IDS_DETECTOR']="";
           this.data.formData['COUNT_IDS_WARNING_DEVICE']="";
           this.data.formData['COUNT_IDS_REMOTE_CONSOLE']="";
           this.data.formData['COUNT_IDS_DURESS_BUTTON']="";
           this.data.formData['COUNT_IDS_WINDOW_SENSOR']="";
           this.data.formData['COUNT_IDS_GLASS_BREAK_SENSOR']="";
           this.data.formData['COUNT_IDS_DOOR_SENSOR']="";
           this.data.formData['LAST_TEST_DATE']="";
           this.data.formData['LAST_TEST_USER_ID']="";
           this.data.formData['TEST_DUE_DATE']="";
           this.data.formData['TEST_DAYS']="";
           this.data.formData['STATE']="";
           this.data.formData['ZIP']="";
           this.data.formData['REP_XRAY_UNIT']="";
      } else {
           this.editing='Y';
           this.testing='N'
           this.addcomponents='N'
           this.data.formData=id;
      }
    }

    ngOnInit(): void
    {   
      this.posting='N';
      this.editing='N';
      this.testing='N';
      this.showingFacility="N";
      this.dsc='';
      this.doc_title="";   
      this.editQQ='N';
      this.uploading='N';
      this.doctype='';
      this.stddoc='';
      this.title='';
      this.assetid=';'
      this.addcomponents='N';
      this.panelOpen=[{ value:0, open:'N'}];
      this.panelOpen.push(0);
      console.log(this.panelOpen);    
      this._activatedRoute.data.subscribe(({ 
        data, menudata, userdata })=> { 
          this.data=data;
          this.user=userdata;
          this.navigation=menudata
          this.data.pacs.forEach(function (value: any) {
            value.BARCODE='N';
          });
      }) 
            this.adding='N';

            this._fuseMediaWatcherService.onMediaChange$
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(({matchingAliases}) => {
                this.isScreenSmall = !matchingAliases.includes('md');
            });
              
    }

    ngOnDestroy(): void
    {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next(null);
        this._unsubscribeAll.complete();
    }

    trackByFn(index: number, item: any): any
    {
        return item.id || index;
    }


    private _fixSvgFill(element: Element): void
    {
        // Current URL
        const currentURL = this._router.url;

        // 1. Find all elements with 'fill' attribute within the element
        // 2. Filter out the ones that doesn't have cross reference so we only left with the ones that use the 'url(#id)' syntax
        // 3. Insert the 'currentURL' at the front of the 'fill' attribute value
        Array.from(element.querySelectorAll('*[fill]'))
             .filter(el => el.getAttribute('fill').indexOf('url(') !== -1)
             .forEach((el) => {
                 const attrVal = el.getAttribute('fill');
                 el.setAttribute('fill', `url(${currentURL}${attrVal.slice(attrVal.indexOf('#'))}`);
             });
    }

    showUpload(id) {
      if (this.uploading=='Y') {
        this.uploading="N";
        this.assetid="";
      } else {
        this.uploading="Y";
        this.assetid=id;
      }
    }

    toggleNavigation(name: string): void
    {
        // Get the navigation
        const navigation = this._fuseNavigationService.getComponent<FuseVerticalNavigationComponent>(name);

        if ( navigation )
        {
            // Toggle the opened status
            navigation.toggle();
        }
    }

    getFormFieldHelpersAsString(): string
    {
        return this.formFieldHelpers.join(' ');
    }


    editBlur(id: any) {
      this.data.colForm['message_'+id]="";
    }

    postForm() {
      //
      // Post Edit Asset
      //
      this._dataService.postForm("post-edit-asset", this.data['formData']).subscribe((data:any)=>{
          this.data=data;
          this.data.pacs.forEach(function (value: any) {
            //
            // Close all panels after save.
            //
            value.BARCODE='N';
          });
          this.testing='N';
          this.posting='N';
          this.editing='Y';
        });
      }

      deleteComponent() {
       if (confirm("Are you sure you want to delete this component?"))  {
        this._dataService.postForm("post-delete-asset", this.data['formData']).subscribe((data:any)=>{
          this.data=data;
          this.testing='N';
          this.posting='N';
          this.editing='Y';
        });
       }
      }

      postForm2() {
        //
        // Post Add/Edit Functionality Test
        //
        if (this.posting=='N') {
             this.posting='Y';
             this._dataService.postForm("post-bsa-test", this.data['formData2']).subscribe((data:any)=>{
             this.data=data;
             this.posting='N';
             this.editing='N';
             this.addcomponents='N';
             this.testing='N';
             this.data.pacs.forEach(function (value: any) {
                value.BARCODE='N';
              });
            });
        }
      }

      postAddComponents() {
        //
        // Post Add Components
        //
        if (this.posting=='N') {
          this.posting='Y';
          this._dataService.postForm("post-add-compnents", this.data['formData3']).subscribe((data:any)=>{
          this.data=data;
          this.posting='N';
          this.editing='N';
          this.addcomponents='N';
          this.testing='N';
          this.uploading='N';
          this.data.pacs.forEach(function (value: any) {
             value.BARCODE='N';
          });
         });
     }
    }

  editE(id: any,first_name: any, middle_name: any, last_name: any, suffix: any, email: any, phone_mobile: any, date_of_birth: any, social_security_number: any, gender: any) {
    if (this.adding=='N') {
      this.adding='Y';
    this.data.employeeData['id']=id;
    this.data.employeeData['first_name']=first_name;
    this.data.employeeData['middle_name']=middle_name;
    this.data.employeeData['last_name']=last_name;
    this.data.employeeData['suffix']=suffix;
    this.data.employeeData['email']=email;
    this.data.employeeData['phone_mobile']=phone_mobile;
    this.data.employeeData['social_security_number']=social_security_number;
    this.data.employeeData['date_of_birth']=date_of_birth;
    this.data.employeeData['gender']=gender;    
    } else {
    this.adding='N';
    this.data.employeeData['id']="";
    this.data.employeeData['first_name']="";
    this.data.employeeData['middle_name']="";
    this.data.employeeData['last_name']="";
    this.data.employeeData['suffix']="";
    this.data.employeeData['email']="";
    this.data.employeeData['phone_mobile']="";
    this.data.employeeData['social_security_number']="";
    this.data.employeeData['date_of_birth']="";
    this.data.employeeData['gender']="";
    }  
  }

      //------------------------------
      // Upload Form
      //------------------------------

      file=new FormControl('')
      file_data:any=''

      fileChange(index,event) {
        
        const fileList: FileList = event.target.files;
        //check whether file is selected or not
        if (fileList.length > 0) {
            const file = fileList[0];
            //get file information such as name, size and type
            console.log('finfo',file.name,file.size,file.type);
            //max file size is 8 mb
            if((file.size/1048576)<=8)
            {
              this.uploadData = new FormData();
              this.uploadData.append('file', file, file.name);
              this.uploadData.append('assetid',this.assetid);
              this.uploadData.append('stddoc',this.stddoc);
              this.uploadData.append('title',this.title);             
            } else {
              alert('File size exceeds 8 MB. Please choose less than 8 MB');
            }
            
        }
    
      }
      
      uploadFile() {
          this.uploadData.append('assetid',this.assetid);
          this.uploadData.append('stddoc',this.stddoc);
          this.uploadData.append('title',this.title);
          this.uploadData.append('doc_title',this.doc_title);
          this.file_data=this.uploadData
            this._dataService.postUpload(this.file_data).subscribe((data:any)=>{
            this.data=data;
            this.posting='N';
            this.editing='N';
            this.addcomponents='N';
            this.testing='N';
            this.uploading='N';
            this.data.pacs.forEach(function (value: any) {
               value.BARCODE='N';
            });
           });
       }

//          this.http.post('data/upload.php',this.file_data)
//          .subscribe(res => {
//            this.data=data;
//            this.posting='N';
//            this.editing='N';
//            this.addcomponents='N';
//            this.testing='N';
//            this.uploading='N';
//          }, (err) => {
//          //send error response
//          alert('error occured')
//        });
//        }

        showDoc(id: any) {
          window.open('https://myna-docs.com/?id='+id,'_new')
        }

  }
  