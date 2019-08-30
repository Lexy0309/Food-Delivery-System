package com.dinosoftlabs.foodies.android.Notifications;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v4.content.LocalBroadcastManager;
import android.text.TextUtils;
import android.util.Log;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.MainActivity;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RestReveiwActivity;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RiderReviewActivity;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Utils.NotificationUtils;


import org.json.JSONException;
import org.json.JSONObject;

public class MyFirebaseMessagingService extends FirebaseMessagingService {
    private static final String TAG = MyFirebaseMessagingService.class.getSimpleName();

    private NotificationUtils notificationUtils;
    String channelId = "channel-01";

    String restaurant_id,restaurant_name,isBackground,imageUrl,timestamp,rider_name,order_id,rider_user_id;

    SharedPreferences sPref;
    private LocalBroadcastManager broadcaster;

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {

        sPref = getSharedPreferences(PreferenceClass.user,MODE_PRIVATE);

        Log.e(TAG, "From: " + remoteMessage.getFrom());

        if (remoteMessage == null)
            return;

      /*  // Check if message contains a notification payload.
        if (remoteMessage.getNotification() != null) {
            Log.e(TAG, "Notification Body: " + remoteMessage.getNotification().getBody());
            handleNotification(remoteMessage.getNotification().getBody());
        }
*/
        // Check if message contains a data payload.
        if (remoteMessage.getData().size() > 0) {
            Log.e(TAG, "Data Payload: " + remoteMessage.getData().toString());

            try {
                JSONObject json = new JSONObject(remoteMessage.getData());
                handleDataMessage(json);
            } catch (Exception e) {
                Log.e(TAG, "Exception: " + e.getMessage());
            }
        }

    }



    private void handleNotification(String message) {
        if (!NotificationUtils.isAppIsInBackground(getApplicationContext())) {
            // app is in foreground, broadcast the push message
            Intent pushNotification = new Intent(Config.PUSH_NOTIFICATION);
            pushNotification.putExtra("message", message);
            LocalBroadcastManager.getInstance(this).sendBroadcast(pushNotification);

            // play notification sound
            NotificationUtils notificationUtils = new NotificationUtils(getApplicationContext(),channelId);
            notificationUtils.playNotificationSound();

        }else{
            // If the app is in background, firebase itself handles the notification
        }
    }

    private void handleDataMessage(JSONObject json) {


        Log.e(TAG, "push json: " + json.toString());
        SharedPreferences.Editor editor = sPref.edit();

        try {
          //  JSONObject data = json.getJSONObject("data");

            if(!json.getString("type").isEmpty()) {
                String type = json.getString("type");

                if (type.equalsIgnoreCase("order_review")) {

                    restaurant_id = json.getString("restaurant_id");
                    restaurant_name = json.getString("restaurant_name");
                  //  isBackground = json.getString("is_background");
                    imageUrl = json.getString("img");

                    editor.putString(PreferenceClass.RESTAURANT_ID_NOTIFY, restaurant_id);
                    editor.putString(PreferenceClass.RESTAURANT_NAME_NOTIFY, restaurant_name);
                    editor.putString(PreferenceClass.REVIEW_IMG_PIC,imageUrl);
                    editor.putString(PreferenceClass.REVIEW_TYPE, "order_review");

                    editor.commit();

                    Intent restIntent = new Intent(this, RestReveiwActivity.class);
                    restIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(restIntent);

                    Intent intent = new Intent("MyData");
                    intent.putExtra("type", type);
                    LocalBroadcastManager.getInstance(this).sendBroadcast(intent);


                } else if(type.equalsIgnoreCase("rider_review")) {
                    rider_user_id = json.getString("rider_user_id");
                    order_id = json.getString("order_id");
                    rider_name = json.getString("rider_name");
                  //  isBackground = json.getString("is_background");
                   // imageUrl = json.getString("img");
                    //   timestamp = remoteMessage.getData().get("timestamp");

                    editor.putString(PreferenceClass.RIDER_USER_ID_NOTIFY, rider_user_id);
                    editor.putString(PreferenceClass.ORDER_ID_NOTIFY, order_id);
                    editor.putString(PreferenceClass.RIDER_NAME_NOTIFY, rider_name);
                    editor.putString(PreferenceClass.REVIEW_TYPE, "rider_review");
                    editor.commit();

                    Intent riderIntent = new Intent(this, RiderReviewActivity.class);
                    riderIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(riderIntent);
                    Intent intent = new Intent("MyData");
                    intent.putExtra("type", type);
                    LocalBroadcastManager.getInstance(this).sendBroadcast(intent);


                }

            }
            else {
                String title = json.getString("title");

                if (!NotificationUtils.isAppIsInBackground(getApplicationContext())) {
                    // app is in foreground, broadcast the push message
                    Intent pushNotification = new Intent(Config.PUSH_NOTIFICATION);
                    pushNotification.putExtra("message", title);
                    LocalBroadcastManager.getInstance(this).sendBroadcast(pushNotification);

                    // play notification sound
                    NotificationUtils notificationUtils = new NotificationUtils(getApplicationContext(), channelId);
                    notificationUtils.playNotificationSound();
                } else {
                    // app is in background, show the notification in notification tray
                    Intent resultIntent = new Intent(getApplicationContext(), MainActivity.class);
                    resultIntent.putExtra("message", title);

                    // check for image attachment
                    if (TextUtils.isEmpty(imageUrl)) {
                        showNotificationMessage(getApplicationContext(), title, title, timestamp, resultIntent);
                    } else {
                        // image is present, show notification with image
                        showNotificationMessageWithBigImage(getApplicationContext(), title, title, timestamp, resultIntent, imageUrl);
                    }
                }
            }
            } catch (JSONException e) {
                Log.e(TAG, "Json Exception: " + e.getMessage());
            } catch (Exception e) {
                Log.e(TAG, "Exception: " + e.getMessage());
            }





     /* //  Log.e(TAG, "push json: " + json.toString());

        try {

            SharedPreferences.Editor editor = sPref.edit();

            String data = remoteMessage.getData().toString();

           String title = remoteMessage.getData().get("title");
            timestamp = remoteMessage.getData().get("timestamp");

            if(remoteMessage.getData().get("type")!=null) {
                String type = remoteMessage.getData().get("type");

                if (type.equalsIgnoreCase("rider_review")) {

                    restaurant_id = remoteMessage.getData().get("restaurant_id");
                    restaurant_name = remoteMessage.getData().get("restaurant_name");
                    isBackground = remoteMessage.getData().get("is_background");
                    imageUrl = remoteMessage.getData().get("image");

                    editor.putString(PreferenceClass.RESTAURANT_ID_NOTIFY, restaurant_id);
                    editor.putString(PreferenceClass.RESTAURANT_NAME_NOTIFY, restaurant_name);
                    editor.putString(PreferenceClass.REVIEW_TYPE, "rider_review");

                    editor.commit();

                    Intent intent = new Intent("MyData");
                    intent.putExtra("type", type);
                    broadcaster.sendBroadcast(intent);


                } else {
                    rider_user_id = remoteMessage.getData().get("rider_user_id");
                    order_id = remoteMessage.getData().get("order_id");
                    rider_name = remoteMessage.getData().get("rider_name");
                    isBackground = remoteMessage.getData().get("is_background");
                    imageUrl = remoteMessage.getData().get("image");
                 //   timestamp = remoteMessage.getData().get("timestamp");

                    editor.putString(PreferenceClass.RIDER_USER_ID_NOTIFY, rider_user_id);
                    editor.putString(PreferenceClass.ORDER_ID_NOTIFY, order_id);
                    editor.putString(PreferenceClass.RIDER_NAME_NOTIFY, rider_name);
                    editor.putString(PreferenceClass.REVIEW_TYPE, "");
                    editor.commit();

                    Intent intent = new Intent("MyData");
                    intent.putExtra("type", type);
                    broadcaster.sendBroadcast(intent);


                }

            }

            if (!NotificationUtils.isAppIsInBackground(getApplicationContext())) {
                // app is in foreground, broadcast the push message
                Intent pushNotification = new Intent(Config.PUSH_NOTIFICATION);
                pushNotification.putExtra("message", title);
                LocalBroadcastManager.getInstance(this).sendBroadcast(pushNotification);

                // play notification sound
                NotificationUtils notificationUtils = new NotificationUtils(getApplicationContext(),channelId);
                notificationUtils.playNotificationSound();
            } else {
                // app is in background, show the notification in notification tray
                Intent resultIntent = new Intent(getApplicationContext(), MainActivity.class);
                resultIntent.putExtra("message", title);

                // check for image attachment
                if (TextUtils.isEmpty(imageUrl)) {
                    showNotificationMessage(getApplicationContext(), title, restaurant_name, timestamp, resultIntent);
                } else {
                    // image is present, show notification with image
                    showNotificationMessageWithBigImage(getApplicationContext(), title, restaurant_name, timestamp, resultIntent, imageUrl);
                }
            }
*/
           /* JSONObject payload = remoteMessage.getData().get("payload");

            JSONObject jsonObject = new JSONObject(String.valueOf(remoteMessage));

           JSONObject data = jsonObject.getJSONObject("data");*/

           /* String title = data.getString("title");
            String message = data.getString("message");*/


         /*   Log.e(TAG, "title: " + title);
            Log.e(TAG, "message: " + message);
            Log.e(TAG, "isBackground: " + isBackground);
          //  Log.e(TAG, "payload: " + payload.toString());
            Log.e(TAG, "imageUrl: " + imageUrl);
            Log.e(TAG, "timestamp: " + timestamp);*/

    }

    /**
     * Showing notification with text only
     */
    private void showNotificationMessage(Context context, String title, String message, String timeStamp, Intent intent) {
        notificationUtils = new NotificationUtils(context,channelId);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        notificationUtils.showNotificationMessage(title, message, timeStamp, intent);
    }

    /**
     * Showing notification with text and image
     */
    private void showNotificationMessageWithBigImage(Context context, String title, String message, String timeStamp, Intent intent, String imageUrl) {
        notificationUtils = new NotificationUtils(context,channelId);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        notificationUtils.showNotificationMessage(title, message, timeStamp, intent, imageUrl);
    }

}
