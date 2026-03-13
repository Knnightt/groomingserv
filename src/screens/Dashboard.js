import React from 'react';
import { ScrollView, View, Text, Image, StyleSheet, TouchableOpacity } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { ROUTES } from '../utils';

const Dashboard = () => {
  const navigation = useNavigation();

  const handleBooking = () => {
    // placeholder for booking action
    console.log('Booking button pressed');
  };

  return (
    <ScrollView style={styles.container}>
      {/* Simple header bar */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Quibot's Grooming</Text>
        <TouchableOpacity onPress={() => navigation.navigate(ROUTES.PROFILE)}>
          <Text style={styles.headerLink}>Profile</Text>
        </TouchableOpacity>
      </View>
      {/* Hero section */}
      <Image
        source={{ uri: 'https://i.imgur.com/4NJl8sD.jpg' }} // sleeping dog placeholder
        style={styles.heroImage}
        resizeMode="cover"
      />
      <View style={styles.heroOverlay} />
      <View style={styles.heroContent}>
        <Text style={styles.heroTitle}>Pamper Your Pets with Gentle, Professional Care</Text>
        <Text style={styles.heroSubtitle}>All grooming packages tailored for your furry friend's specific needs.</Text>
        <View style={styles.heroButtons}>
          <TouchableOpacity style={styles.heroButton} onPress={handleBooking}>
            <Text style={styles.heroButtonText}>Book Appointment</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Services */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Our Services</Text>
        <Text style={styles.sectionSubtitle}>We guarantee consistent quality and reliable service across all touchpoints.</Text>
        <View style={styles.cardRow}>
          <View style={styles.card}>
            <Text style={styles.cardIcon}>✂️</Text>
            <Text style={styles.cardTitle}>Full Grooming</Text>
            <Text style={styles.cardText}>Complete grooming package from head to tail</Text>
          </View>
          <View style={styles.card}>
            <Text style={styles.cardIcon}>💧</Text>
            <Text style={styles.cardTitle}>Spa & Bath</Text>
            <Text style={styles.cardText}>Luxurious spa treatments for your pet</Text>
          </View>
        </View>
      </View>

      {/* Footer simple */}
      <View style={styles.footer}>
        <Text style={styles.footerText}>&copy; 2023 Quibot's Grooming Services</Text>
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  heroImage: {
    height: 250,
    width: '100%',
    position: 'absolute',
    top: 0,
  },
  heroOverlay: {
    position: 'absolute',
    top: 0,
    width: '100%',
    height: 250,
    backgroundColor: 'rgba(0,0,0,0.4)',
  },
  heroContent: {
    height: 250,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 20,
  },
  header: {
    height: 60,
    backgroundColor: '#fff',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  headerLink: {
    color: '#007AFF',
  },
  heroTitle: {
    color: '#fff',
    fontSize: 22,
    fontWeight: 'bold',
    textAlign: 'center',
  },
  heroSubtitle: {
    color: '#fff',
    fontSize: 14,
    marginTop: 8,
    textAlign: 'center',
  },
  heroButtons: {
    flexDirection: 'row',
    marginTop: 15,
  },
  heroButton: {
    backgroundColor: '#ff8c00',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 20,
  },
  heroButtonText: {
    color: '#fff',
    fontWeight: '600',
  },
  section: {
    padding: 20,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 5,
    textAlign: 'center',
  },
  sectionSubtitle: {
    fontSize: 14,
    color: '#666',
    marginBottom: 15,
    textAlign: 'center',
  },
  cardRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  card: {
    width: '45%',
    backgroundColor: '#fafafa',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10,
  },
  cardIcon: {
    fontSize: 30,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: '600',
    marginTop: 10,
  },
  cardText: {
    fontSize: 12,
    color: '#666',
    textAlign: 'center',
    marginTop: 5,
  },
  footer: {
    padding: 20,
    alignItems: 'center',
    backgroundColor: '#f0f0f0',
  },
  footerText: {
    color: '#888',
    fontSize: 12,
  },
});

export default Dashboard;