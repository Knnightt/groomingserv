import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Dimensions,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useDispatch } from 'react-redux';
import { logout } from '../app/reducers/authReducer';
import { ROUTES } from '../utils';
import Sidebar from '../components/Sidebar';
import CustomButton from '../components/CustomButton';

const { width } = Dimensions.get('window');
const isTablet = width >= 768;

const AppointmentsScreen = () => {
  const navigation = useNavigation();
  const dispatch = useDispatch();

  const handleLogout = () => {
    dispatch(logout());
  };

  const handleNavigate = (route) => {
    if (route === 'Dashboard') {
      navigation.navigate(ROUTES.DASHBOARD);
    } else if (route === 'Profile') {
      navigation.navigate(ROUTES.PROFILE);
    } else if (route === 'MyPets') {
      navigation.navigate(ROUTES.MY_PETS);
    } else if (route === 'Settings') {
      navigation.navigate(ROUTES.SETTINGS);
    }
  };

  // Mock appointments data
  const appointments = [];

  return (
    <View style={styles.container}>
      {isTablet && (
        <Sidebar
          activeRoute="Appointments"
          onNavigate={handleNavigate}
          onLogout={handleLogout}
        />
      )}

      <View style={styles.mainContent}>
        <View style={styles.header}>
          <Text style={styles.headerTitle}>Appointments</Text>
        </View>

        <ScrollView style={styles.scrollContent} showsVerticalScrollIndicator={false}>
          <View style={styles.titleSection}>
            <Text style={styles.pageTitle}>Your Appointments</Text>
            <Text style={styles.pageSubtitle}>
              Manage and view all your grooming appointments
            </Text>
          </View>

          {/* Filter Tabs */}
          <View style={styles.filterTabs}>
            <TouchableOpacity style={[styles.filterTab, styles.filterTabActive]}>
              <Text style={[styles.filterTabText, styles.filterTabTextActive]}>
                Upcoming
              </Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.filterTab}>
              <Text style={styles.filterTabText}>Past</Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.filterTab}>
              <Text style={styles.filterTabText}>Cancelled</Text>
            </TouchableOpacity>
          </View>

          {/* Appointments List */}
          {appointments.length === 0 ? (
            <View style={styles.emptyState}>
              <View style={styles.emptyIcon}>
                <Text style={styles.emptyIconText}>📅</Text>
              </View>
              <Text style={styles.emptyTitle}>No Appointments Yet</Text>
              <Text style={styles.emptySubtitle}>
                Book your first grooming appointment and give your pet the care they deserve.
              </Text>
              <CustomButton
                label="Book Appointment"
                onPress={() => {}}
                buttonStyle={styles.bookButton}
                textStyle={styles.bookButtonText}
              />
            </View>
          ) : (
            <View style={styles.appointmentsList}>
              {/* Appointments would be mapped here */}
            </View>
          )}
        </ScrollView>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: 'row',
    backgroundColor: '#F8F9FA',
  },
  mainContent: {
    flex: 1,
    backgroundColor: '#F8F9FA',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 24,
    paddingVertical: 16,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#E8E8E8',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: '700',
    color: '#333333',
  },
  scrollContent: {
    flex: 1,
    padding: 24,
  },
  titleSection: {
    marginBottom: 24,
  },
  pageTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 8,
  },
  pageSubtitle: {
    fontSize: 14,
    color: '#6B8BB8',
  },
  filterTabs: {
    flexDirection: 'row',
    marginBottom: 24,
    gap: 12,
  },
  filterTab: {
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 20,
    backgroundColor: '#FFFFFF',
    borderWidth: 1,
    borderColor: '#E8E8E8',
  },
  filterTabActive: {
    backgroundColor: '#6B8BB8',
    borderColor: '#6B8BB8',
  },
  filterTabText: {
    fontSize: 14,
    color: '#666666',
    fontWeight: '500',
  },
  filterTabTextActive: {
    color: '#FFFFFF',
  },
  emptyState: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 48,
    alignItems: 'center',
  },
  emptyIcon: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#F0F4F8',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 24,
  },
  emptyIconText: {
    fontSize: 36,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 8,
  },
  emptySubtitle: {
    fontSize: 14,
    color: '#666666',
    textAlign: 'center',
    marginBottom: 24,
    maxWidth: 300,
  },
  bookButton: {
    backgroundColor: '#6B8BB8',
    paddingVertical: 14,
    paddingHorizontal: 32,
    borderRadius: 25,
  },
  bookButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  appointmentsList: {
    gap: 16,
  },
});

export default AppointmentsScreen;
