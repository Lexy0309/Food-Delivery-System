package com.dinosoftlabs.foodies.android.RActivitiesAndFragments.Services;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;

public class LocationMgr implements GoogleApiClient.ConnectionCallbacks, GoogleApiClient.OnConnectionFailedListener {

    private final Context context;
    private GoogleApiClient googleApiClient;
    private boolean inProgress;

    public enum REQUEST_TYPE {START, STOP, LAST_KNOWN}

    private REQUEST_TYPE requestType;
    private Intent intent;
    LocationRequest mLocationRequest;
    LocationListener ll;


    public LocationMgr(Context context) {
        this.context = context;
        this.googleApiClient = new GoogleApiClient.Builder(context)
                .addApi(LocationServices.API)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .build();
        intent = new Intent(context, UpdateLocation.class);
        inProgress = false;
    }

    @Override
    public void onConnectionFailed(ConnectionResult arg0) {
        inProgress = false;
    }

    @Override
    public void onConnectionSuspended(int arg0) {
        googleApiClient.connect();
    }

    @Override
    public void onConnected(Bundle connectionHint) {
        PendingIntent pendingIntent = PendingIntent.getService(context, 123, intent, PendingIntent.FLAG_UPDATE_CURRENT);


        switch (requestType) {


            case START:
                if (ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                    // TODO: Consider calling
                    //    ActivityCompat#requestPermissions
                    // here to request the missing permissions, and then overriding
                    //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                    //                                          int[] grantResults)
                    // to handle the case where the user grants the permission. See the documentation
                    // for ActivityCompat#requestPermissions for more details.
                    return;
                }
                LocationServices.FusedLocationApi.requestLocationUpdates(googleApiClient, mLocationRequest, pendingIntent);
                break;

            case STOP:
                LocationServices.FusedLocationApi.removeLocationUpdates(googleApiClient, pendingIntent);
                break;

            case LAST_KNOWN:

                Location location = LocationServices.FusedLocationApi.getLastLocation(googleApiClient);
                ll.onLocationChanged(location);
                break;

            default :
               // Log.e("Unknown request type in onConnected().","");
                break;
        }

        inProgress = false;
        googleApiClient.disconnect();

    }



    /**
     *
     * @param frequency (minutes) minimum time interval between location updates
     */
    @SuppressLint("RestrictedApi")
    public void requestLocationUpdates(int frequency)
    {
        mLocationRequest = new LocationRequest();
        mLocationRequest.setInterval(frequency * 1000);
        mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        mLocationRequest.setFastestInterval(1000);
        if (inProgress)
        {
          //  log.e("A request is already underway");
            return;
        }
        inProgress = true;
        requestType = REQUEST_TYPE.START;
        googleApiClient.connect();
    }

    public void removeContinuousUpdates()
    {
        if (inProgress)
        {
           // log.e("A request is already underway");
            return;
        }
        inProgress = true;
        requestType = REQUEST_TYPE.STOP;
        googleApiClient.connect();
    }

    public void getLastKnownLocation(LocationListener ll)
    {
        this.ll = ll;
        if (inProgress)
        {
          //  log.e("A request is already underway");
            return;
        }
        inProgress = true;
        requestType = REQUEST_TYPE.LAST_KNOWN;
        googleApiClient.connect();

    }

}
