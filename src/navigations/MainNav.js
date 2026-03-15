import { createStackNavigator } from '@react-navigation/stack';
import { ROUTES } from '../utils';

// screens
import Dashboard from '../screens/Dashboard';
import ProfileScreen from '../screens/ProfileScreen';
import AppointmentsScreen from '../screens/AppointmentsScreen';
import MyPetsScreen from '../screens/MyPetsScreen';
import SettingsScreen from '../screens/SettingsScreen';

const Stack = createStackNavigator();

const MainNavigation = () => {
  return (
    <Stack.Navigator 
      initialRouteName={ROUTES.DASHBOARD}
      screenOptions={{
        headerShown: false,
      }}
    >
      <Stack.Screen 
        name={ROUTES.DASHBOARD} 
        component={Dashboard} 
      />
      <Stack.Screen 
        name={ROUTES.PROFILE} 
        component={ProfileScreen} 
      />
      <Stack.Screen 
        name={ROUTES.APPOINTMENTS} 
        component={AppointmentsScreen} 
      />
      <Stack.Screen 
        name={ROUTES.MY_PETS} 
        component={MyPetsScreen} 
      />
      <Stack.Screen 
        name={ROUTES.SETTINGS} 
        component={SettingsScreen} 
      />
    </Stack.Navigator>
  );
};

export default MainNavigation;
